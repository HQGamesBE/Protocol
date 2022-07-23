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
 * Class DisconnectPacket
 * @package HQGames\network\protocol
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 16:01
 * @ide PhpStorm
 * @project Bridge
 */
class DisconnectPacket extends CloudPacket{
	public string $reason;

	/**
	 * @generate-build-method
	 * Function build
	 * @var string $reason
	 * @return DisconnectPacket
	 */
	#[Pure] public static function build(string $reason): DisconnectPacket{
		$result = new self;
		$result->reason = $reason;
		return $result;
	}

	/**
	 * Function getPacketType
	 * @return string
	 */
	public static function getPacketType(): string{
		return "Disconnect";
	}

	/**
	 * Function handle
	 * @param IPacketHandler $handler
	 * @return void
	 */
	public function handle(IPacketHandler $handler): void{
		$handler->handleDisconnectPacket($this);
	}
}
