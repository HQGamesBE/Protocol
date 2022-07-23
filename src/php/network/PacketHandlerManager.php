<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network;
use HQGames\Bridge\Bridge;
use HQGames\Bridge\Cache;
use HQGames\Bridge\utils\BackendProperties;
use HQGames\network\types\CloudPacket;
use HQGames\plugin\ICloudy;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;


/**
 * Class PacketHandlerManager
 * @package HQGames\network
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 14:50
 * @ide PhpStorm
 * @project Bridge
 */
class PacketHandlerManager{
	use SingletonTrait {
		setInstance as private static;
		reset as private;
	}

	/** @var IPacketHandler[] */
	private array $packetHandlers = [];

	/**
	 * PacketHandlerManager constructor.
	 * @param CloudConnection $cloudConnection
	 */
	public function __construct(private CloudConnection $cloudConnection){
	}

	/**
	 * Function registerPacketHandler
	 * @param IPacketHandler $packetHandler
	 * @return void
	 */
	public function registerPacketHandler(IPacketHandler $packetHandler): void{
		$this->packetHandlers[get_class($packetHandler)] = $packetHandler;
	}

	/**
	 * Function unregisterPacketHandler
	 * @param IPacketHandler $packetHandler
	 * @return void
	 */
	public function unregisterPacketHandler(IPacketHandler $packetHandler): void{
		unset($this->packetHandlers[get_class($packetHandler)]);
	}

	private function handlePacket(CloudPacket $packet): void{
		foreach ($this->packetHandlers as $packetHandler)
			$packet->handle($packetHandler);
	}

	/**
	 * Function handle
	 * @param array $data
	 * @return void
	 */
	public function handle(array $data): void{
		if (!isset($data["type"])) return;
		$required = [
			"connect"    => [ "secret", "identifier", "success" ],
			"disconnect" => [ "identifier" ],
		];
		if (!isset($required[$data["type"]])) {
			$this->cloudConnection->getLogger()->error("Unknown packet type: " . $data["type"]);
			return;
		}
		$missing = array_diff($required[$data["type"]], array_keys($data));
		if (count($missing) > 0) {
			$this->cloudConnection->getLogger()->error("Missing packet data: " . implode(", ", $missing));
			return;
		}
		switch (mb_strtolower($data["type"])) {
			case "connect":
				Cache::getInstance()->secret = $data["secret"];
				BackendProperties::getInstance()->overwriteIdentifier($data["identifier"]);
				$this->cloudConnection->getLogger()->debug("Connected to cloud");
				foreach (Server::getInstance()->getPluginManager()->getPlugins() as $plugin) {
					if ($plugin instanceof ICloudy) $plugin->onCloudConnect();
				}
				break;
			case "disconnect":
				$this->cloudConnection->getLogger()->info("Disconnected fom cloud");
				foreach (Server::getInstance()->getPluginManager()->getPlugins() as $plugin) {
					if ($plugin instanceof ICloudy) $plugin->onCloudDisconnect();
				}
				Server::getInstance()->shutdown();
				break;
			case "pong":
				Bridge::getInstance()->last_pong = time();
				$this->cloudConnection->getLogger()->info("Pong received");
				Bridge::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function (): void{
					$this->cloudConnection->writeBuffer(json_encode([ "type"      => "ping",
																	  "timestamp" => time() * 1000,
					]));
				}), 20);
				break;
			case "error":
				$this->handleError($data);
				break;
			default:
				$this->handleError([ "type" => "error", "message" => "Unknown packet type" ]);
				break;
		}
	}

	private function handleError(array $data): void{
		$this->cloudConnection->getLogger()->error("Error received: " . $data["message"]);
		foreach (Server::getInstance()->getPluginManager()->getPlugins() as $plugin) {
			if ($plugin instanceof ICloudy) $plugin->onCloudError($data["message"]);
		}
		Server::getInstance()->shutdown();
	}


	/**
	 * Function getCloudConnection
	 * @return CloudConnection
	 */
	public function getCloudConnection(): CloudConnection{
		return $this->cloudConnection;
	}
}
