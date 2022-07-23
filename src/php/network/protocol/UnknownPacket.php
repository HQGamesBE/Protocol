<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\network\protocol;
use HQGames\network\IPacketHandler;


/**
 * Class UnknownPacket
 * @package HQGames\network\protocol
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 17:58
 * @ide PhpStorm
 * @project Protocol
 */
class UnknownPacket extends \HQGames\network\CloudPacket{
	/**
	 * Function getPacketType
	 * @return string
	 */
	public static function getPacketType(): string{
		return "Unknown";
	}

	/**
	 * Function handle
	 * @param IPacketHandler $handler
	 * @return void
	 */
	public function handle(IPacketHandler $handler): void{
		$handler->handleUnknownPacket($this);
	}
}
