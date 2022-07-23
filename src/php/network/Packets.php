<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\network;
use HQGames\Bridge\Bridge;
use HQGames\Bridge\utils\BackendProperties;
use InvalidArgumentException;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;


/**
 * Class Packets
 * @package HQGames\network
 * @author Jan Sohn / xxAROX
 * @date 11. July, 2022 - 21:08
 * @ide PhpStorm
 * @project Bridge
 */
class Packets{
	use SingletonTrait{
		setInstance as private;
		reset as private;
	}


	/**
	 * Packets constructor.
	 */
	private function __construct(){
	}

	/**
	 * Function connect
	 * @param null|bool $send
	 * @return null|array
	 */
	public function connect(?bool $send = true): ?array{
		$data = [
			"type" => "connect",
			"AuthToken" => BackendProperties::getInstance()->getAuthToken(),
			"success" => null,
			"port" => Server::getInstance()->getPort(),
		];
		if ($send) {
			$this->sendPacket($data);
			return null;
		}
		return $this->completePacket($data);
	}

	/**
	 * Function start_server
	 * @param null|string $template_name
	 * @param string $visibility
	 * @return void
	 */
	public function start_server(string $template_name = null, string $visibility = "public"): void{
		$template_name = $template_name ?? BackendProperties::getInstance()->getTemplate();
	}

	/**
	 * Function error
	 * @param string $reason
	 * @param null|bool $send
	 * @return null|array
	 */
	public function error(string $reason, ?bool $send = true): ?array{
		$data = [
			"type" => "error",
			"reason" => $reason,
		];
		if ($send) {
			$this->sendPacket($data);
			return null;
		}
		return $this->completePacket($data);
	}

	public function raw(array $data, ?bool $send = true): ?array{
		if (!isset($data["type"])) throw new InvalidArgumentException("\$data must be habe a 'type'");
		if ($send) {
			$this->sendPacket($data);
			return null;
		}
		return $this->completePacket($data);
	}

	/**
	 * Function sendPacket
	 * @param array $packet
	 * @return void
	 */
	public function sendPacket(array $packet): void{
		Bridge::getInstance()->getCloudConnection()->writeBuffer(json_encode($this->completePacket($packet)));
	}

	private function completePacket(array $data): array{
		if (!isset($data["type"])) throw new InvalidArgumentException("\$data must be habe a 'type'");
		$data["identifier"] = BackendProperties::getInstance()->getIdentifier();
		return $data;
	}
}
