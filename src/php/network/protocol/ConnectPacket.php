<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network\protocol;
use HQGames\network\CloudPacket;
use HQGames\network\IPacketHandler;
use JetBrains\PhpStorm\Pure;


/**
 * Class ConnectPacket
 * @package HQGames\network\protocol
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 15:48
 * @ide PhpStorm
 * @project Bridge
 */
class ConnectPacket extends CloudPacket{
	public string $AuthToken;

	/**
	 * @generate-build-method
	 * Function build
	 * @var string $AuthToken
	 * @return ConnectPacket
	 */
	#[Pure] public static function build(string $AuthToken): ConnectPacket{
		$result = new self;
		$result->AuthToken = $AuthToken;
		return $result;
	}

	/**
	 * Function getPacketType
	 * @return string
	 */
	public static function getPacketType(): string{
		return "Connect";
	}

	/**
	 * Function handle
	 * @param IPacketHandler $handler
	 * @return void
	 */
	public function handle(IPacketHandler $handler): void{
		$handler->handleConnectPacket($this);
	}
}
