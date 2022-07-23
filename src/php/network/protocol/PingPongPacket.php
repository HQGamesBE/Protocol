<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network\protocol;
use HQGames\network\CloudPacket;
use JetBrains\PhpStorm\Pure;


/**
 * Class PingPongPacket
 * @package HQGames\network\protocol
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 18:04
 * @ide PhpStorm
 * @project Protocol
 */
class PingPongPacket extends CloudPacket{
	public int $timestamp;
	public int $received;

	/**
	 * @generate-build-method
	 * Function build
	 * @var int $timestamp
	 * @var int $received
	 * @return PingPongPacket
	 */
	#[Pure] public static function build(int $timestamp, int $received): PingPongPacket{
		$result = new self;
		$result->timestamp = $timestamp;
		$result->received = $received;
		return $result;
	}

	/**
	 * Function getPacketType
	 * @return string
	 */
	public static function getPacketType(): string{
		return "Ping";
	}

	/**
	 * @inheritDoc
	 */
	public function handle(\HQGames\network\IPacketHandler $handler): void{
		// TODO: Implement handle() method.
	}
}
