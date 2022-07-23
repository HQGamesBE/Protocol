<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\network;
use HQGames\Bridge\utils\BackendProperties;
use HQGames\network\IPacketHandler;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;


/**
 * Class CloudPacket
 * @package HQGames\network
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 15:44
 * @ide PhpStorm
 * @project Bridge
 */
abstract class CloudPacket implements JsonSerializable{

	/**
	 * Function getPacketType
	 * @return string
	 */
	public abstract static function getPacketType(): string;

	/**
	 * Function handle
	 * @param IPacketHandler $handler
	 * @return void
	 */
	public abstract function handle(IPacketHandler $handler): void;

	/**
	 * Function jsonSerialize
	 * @return array
	 */
	public final function jsonSerialize(): array{
		$data =  [
			"type" => static::getPacketType(),
			"identifier" => BackendProperties::getInstance()->getIdentifier(),
		];
		$reflection = new ReflectionClass(static::class);
		$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $property) {
			$name = $property->getName();
			$data[$name] = $this->$name;
		}
		var_dump($data);
		return $data;
	}
}
