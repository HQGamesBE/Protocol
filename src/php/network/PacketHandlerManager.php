<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network;
use pocketmine\utils\SingletonTrait;


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

	public function handlePacket(CloudPacket $packet): void{
		foreach ($this->packetHandlers as $packetHandler)
			$packet->handle($packetHandler);
	}

	/**
	 * Function getCloudConnection
	 * @return CloudConnection
	 */
	public function getCloudConnection(): CloudConnection{
		return $this->cloudConnection;
	}
}
