<?php
declare(strict_types=1);
namespace HQGames\network\protocol;
use HQGames\network\CloudPacket;
use HQGames\network\IPacketHandler;
use JetBrains\PhpStorm\Pure;


/**
 * Class ErrorPacket
 * @package HQGames\network\protocol
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 23:22
 * @ide PhpStorm
 * @project Bridge
 */
class ErrorPacket extends CloudPacket{
	public string $error;
	/**
	 * @generate-build-method
	 * Function build
	 * @var string $error
	 * @return ErrorPacket
	 */
	#[Pure] public static function build(string $error): ErrorPacket{
		$result = new self;
		$result->error = $error;
		return $result;
	}

	/**
	 * Function getPacketType
	 * @return string
	 */
	public static function getPacketType(): string{
		return "Error";
	}

	/**
	 * Function handle
	 * @param IPacketHandler $handler
	 * @return void
	 */
	public function handle(IPacketHandler $handler): void{
		$handler->handleErrorPacket($this);
	}
}
