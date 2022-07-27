<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network;
use HQGames\Bridge\utils\BackendProperties;
use InvalidArgumentException;
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
	public string $identifier;
	public array $data = [];

	/**
	 * Function getPacketType
	 * @return string
	 */
	public abstract static function getPacketType(): string;

	/**
	 * Function encode
	 * @return array
	 */
	public function encode(): array{
		return $this->jsonSerialize();
	}

	/**
	 * Function decode
	 * @param array $data
	 * @return void
	 */
	public function decode(array $data): void{
		$this->jsonUnserialize($data);
		$this->identifier = $data["identifier"] ?? throw new InvalidArgumentException("Missing identifier");
		$this->data = $data["data"] ?? [];
	}

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
		$data = [
			"type"       => mb_strtolower(static::getPacketType()),
			"identifier" => BackendProperties::getInstance()->getIdentifier(),
			"data"       => $this->data,
		];
		$reflection = new ReflectionClass(static::class);
		$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $property) {
			$name = $property->getName();
			if (isset($data[mb_strtolower($name)])) continue;
			$data[mb_strtolower($name)] = $this->$name;
		}
		return array_merge($data, $this->encode());
	}

	/**
	 * Function jsonUnserialize
	 * @param array $data
	 * @return $this
	 */
	public final function jsonUnserialize(array $data): self{
		$reflection = new ReflectionClass($this);
		$properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $property) {
			$name = $property->getName();
			if (isset($data[$name])) {
				$this->$name = $data[$name];
				var_dump($name);
			}
		}
		return $this;
	}
}
