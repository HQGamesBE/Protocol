<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\network;
use Exception;
use HQGames\Bridge\Bridge;
use JetBrains\PhpStorm\Pure;
use PrefixedLogger;
use raklib\utils\InternetAddress;
use RuntimeException;

use function Sodium\add;


/**
 * Class Socket
 * @package HQGames\network
 * @author Jan Sohn / xxAROX
 * @date 11. July, 2022 - 17:16
 * @ide PhpStorm
 * @project Bridge
 */
class Socket{
	private CloudConnection $connection;
	private InternetAddress $address;
	private PrefixedLogger $logger;

	/**
	 * Socket constructor.
	 * @param CloudConnection $connection
	 * @param InternetAddress $address
	 */
	#[Pure] public function __construct(CloudConnection $connection, InternetAddress $address){
		$this->connection = $connection;
		$this->address = $address;
		$this->logger = new PrefixedLogger($connection->getLogger(), "Socket");
	}

	/**
	 * Function connect
	 * @return bool
	 */
	public function connect(): bool{
		$this->getLogger()->notice("Connecting to " . $this->address->getIp() . ":" . $this->address->getPort());
		$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		try {
			if ($socket === false) throw new RuntimeException(socket_strerror(socket_last_error()));
			if (!@socket_connect($socket, $this->address->getIp(), $this->address->getPort())) throw new RuntimeException(socket_strerror(socket_last_error()));
			socket_set_nonblock($socket);
			socket_set_option($socket, SOL_SOCKET, SO_DEBUG, true);
			socket_set_option($socket, SOL_SOCKET, SO_BROADCAST, true);
			socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, true);
			//socket_set_option($socket, SOL_UDP, TCP_NODELAY, 1);
		} catch (Exception $exception) {
			$this->getLogger()->error("Can not connect to Cloud-Socket!");
			$this->getLogger()->logException($exception);
			return false;
		}
		$this->connection->setSocket($socket);
		return true;
	}

	/**
	 * Function close
	 * @return void
	 */
	public function close(): void{
		socket_close($this->connection->getSocket());
	}

	/**
	 * Function getLogger
	 * @return PrefixedLogger
	 */
	public function getLogger(): PrefixedLogger{
		return $this->logger;
	}
}
