<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\network;
use HQGames\network\protocol\ConnectedPacket;
use HQGames\network\protocol\ConnectPacket;
use HQGames\network\protocol\DisconnectPacket;
use HQGames\network\protocol\ErrorPacket;
use HQGames\network\protocol\UnknownPacket;
use InvalidArgumentException;
use pocketmine\utils\SingletonTrait;


/**
 * Class PacketPool
 * @package HQGames\network
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 16:15
 * @ide PhpStorm
 * @project Bridge
 */
class PacketPool{
	use SingletonTrait{
		setInstance as private static;
		reset as private;
	}
	private array $packets = [];

	private function __construct(){
		$this->registerPacket(new UnknownPacket);
		$this->registerPacket(new ErrorPacket);
		$this->registerPacket(new ConnectPacket);
		$this->registerPacket(new ConnectedPacket);
		$this->registerPacket(new DisconnectPacket);
	}

	/**
	 * Function isPacket
	 * @param string $packetType
	 * @return bool
	 */
	public function isPacket(string $packetType): bool{
		return isset($this->packets[mb_strtolower($packetType)]);
	}

	/**
	 * Function getPacket
	 * @param string $packetType
	 * @return CloudPacket
	 */
	public function getPacket(string $packetType): CloudPacket{
		return clone $this->packets[mb_strtolower($packetType)] ?? throw new InvalidArgumentException("Packet $packetType is not registered");
	}

	/**
	 * Function getPacketFromBuffer
	 * @param array $buffer
	 * @return CloudPacket
	 * @throws InvalidArgumentException
	 */
	public function getPacketFromBuffer(array $buffer): CloudPacket{
		$packetType = $buffer["type"] ?? throw new InvalidArgumentException("Packet type is not set");
		return clone $this->packets[mb_strtolower($packetType)] ?? throw new InvalidArgumentException("Packet $packetType is not registered");
	}

	/**
	 * Function registerPacket
	 * @param CloudPacket $packet
	 * @return void
	 */
	public function registerPacket(CloudPacket $packet): void{
		$this->packets[mb_strtolower($packet::getPacketType())] = $packet;
	}
}
