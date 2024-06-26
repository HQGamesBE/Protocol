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
class ConnectedPacket extends CloudPacket{
	public string $secret;
	/** @var Language[] */
	public array $languages;
	public array $players;

	/**
	 * @generate-build-method
	 * Function build
	 * @var string $secret
	 * @var array $languages
	 * @var array $players
	 * @return ConnectedPacket
	 */
	#[Pure] public static function build(string $secret, array $languages, array $players): ConnectedPacket{
		$result = new self;
		$result->secret = $secret;
		$result->languages = $languages;
		$result->players = $players;
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
		$handler->handleConnectedPacket($this);
	}
}
