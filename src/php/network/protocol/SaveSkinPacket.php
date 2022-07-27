<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network\protocol;
use HQGames\network\IPacketHandler;
use JetBrains\PhpStorm\Pure;


/**
 * Class SaveSkinPacket
 * @package HQGames\network\protocol
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 01:19
 * @ide PhpStorm
 * @project Protocol
 */
class SaveSkinPacket extends \HQGames\network\CloudPacket{
	public string $owner = "";
	public string $skinData = "";
	public int $height = 0;
	public int $width = 0;
	public string $geometryName = "";

	/**
	 * @generate-build-method
	 * Function build
	 * @var string $owner
	 * @var string $skinData
	 * @var int $height
	 * @var int $width
	 * @var string $geometryName
	 * @return SaveSkinPacket
	 */
	#[Pure] public static function build(string $owner, string $skinData, int $height, int $width, string $geometryName): SaveSkinPacket{
		$result = new self;
		$result->owner = $owner;
		$result->skinData = $skinData;
		$result->height = $height;
		$result->width = $width;
		$result->geometryName = $geometryName;
		return $result;
	}

	/**
	 * Function getPacketType
	 * @return string
	 */
	public static function getPacketType(): string{
		return "SaveSkin";
	}

	/**
	 * Function handle
	 * @param IPacketHandler $handler
	 * @return void
	 */
	public function handle(IPacketHandler $handler): void{
		$handler->handleSaveSkinPacket($this);
	}

	/**
	 * Function encode
	 * @return array
	 */
	public function encode(): array{
		$data = parent::encode();
		$data["skinData"] = base64_encode($this->skinData);
		return $data;
	}

	/**
	 * Function decode
	 * @param array $data
	 * @return void
	 */
	public function decode(array $data): void{
		parent::decode($data);
		$this->skinData = base64_decode($data["skinData"]);
	}
}
