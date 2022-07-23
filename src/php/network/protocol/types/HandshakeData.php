<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\network\protocol\types;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;


/**
 * Class HandshakeData
 * @package HQGames\network\protocol\types
 * @author Jan Sohn / xxAROX
 * @date 11. July, 2022 - 17:37
 * @ide PhpStorm
 * @project Bridge
 */
class HandshakeData implements JsonSerializable{
	/**
	 * HandshakeData constructor.
	 * @param string $identifier
	 * @param string $authToken
	 */
	public function __construct(private string $identifier, private string $authToken){}

	/**
	 * Function getIdentifier
	 * @return string
	 */
	public function getIdentifier(): string{
		return $this->identifier;
	}

	/**
	 * Function jsonSerialize
	 * @return array
	 */
	#[ArrayShape(["Identifier" => "string", "AuthToken" => "string"])] public function jsonSerialize(): array{
		return [
			"Identifier" => $this->identifier,
			"AuthToken" => $this->authToken,
		];
	}
}
