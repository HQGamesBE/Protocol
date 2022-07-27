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
use HQGames\network\protocol\types\PlayerLoadData;
use JetBrains\PhpStorm\Pure;


/**
 * Class PlayerLoadPacket
 * @package HQGames\network\protocol
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 01:18
 * @ide PhpStorm
 * @project Protocol
 */
class PlayerLoadPacket extends CloudPacket{
	public string $username;
	public string $xuid;
	public PlayerLoadData $playerLoadData;

	/**
	 * @generate-build-method
	 * Function build
	 * @var string $username
	 * @var string $xuid
	 * @var \HQGames\network\protocol\types\PlayerLoadData $playerLoadData
	 * @return PlayerLoadPacket
	 */
	#[Pure] public static function build(string $username, string $xuid, \HQGames\network\protocol\types\PlayerLoadData $playerLoadData): PlayerLoadPacket{
		$result = new self;
		$result->username = $username;
		$result->xuid = $xuid;
		$result->playerLoadData = $playerLoadData;
		return $result;
	}

	/**
	 * Function getPacketType
	 * @return string
	 */
	public static function getPacketType(): string{
		return "PlayerLoad";
	}

	/**
	 * Function handle
	 * @param IPacketHandler $handler
	 * @return void
	 */
	public function handle(IPacketHandler $handler): void{
		$handler->handlePlayerLoadPacket($this);
	}

	public function encode(): array{
		$data = parent::encode();
		$data["playerLoadData"] = $this->playerLoadData->jsonSerialize();
		return $data;
	}

	public function decode(array $data): void{
		parent::decode($data);
		$this->playerLoadData = PlayerLoadData::fromArray($data["playerLoadData"]);
	}
}
