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
use HQGames\Bridge\utils\BackendProperties;
use HQGames\event\packet\CloudPacketResponseEvent;
use HQGames\network\protocol\types\HandshakeData;
use HQGames\network\CloudPacket;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;
use pocketmine\thread\Thread;
use pocketmine\utils\Binary;
use PrefixedLogger;
use raklib\utils\InternetAddress;
use Threaded;
use ThreadedLogger;


/**
 * Class CloudConnection
 * @package HQGames\network
 * @author Jan Sohn / xxAROX
 * @date 11. July, 2022 - 17:16
 * @ide PhpStorm
 * @project Bridge
 */
class CloudConnection extends Thread{
	const STATE_DISCONNECTED = 0;
	const STATE_CONNECTING = 1;
	const STATE_CONNECTED = 2;
	const STATE_AUTHENTICATING = 3;
	const STATE_AUTHENTICATED = 4;
	const STATE_SHUTDOWN = 5;

	private PrefixedLogger $logger;
	private HandshakeData $handshakeData;
	private InternetAddress $address;
	private Socket $cloudSocket;
	private ?\Socket $socket = null;
	private Threaded $input;
	private Threaded $output;
	private string $buffer = '';
	private int $state = self::STATE_DISCONNECTED;
	private int $closeIfNoAcceptedFromCloud = -1;

	/**
	 * CloudConnection constructor.
	 * @param ThreadedLogger $logger
	 * @param HandshakeData $handshakeData
	 */
	public function __construct(ThreadedLogger $logger, HandshakeData $handshakeData){
		$this->logger = new PrefixedLogger($logger, $this->getThreadName());
		$this->handshakeData = $handshakeData;
		$this->address = new InternetAddress(BackendProperties::getInstance()->getBackendAddress(), BackendProperties::getInstance()->getBackendPort(), 4);
		$this->cloudSocket = new Socket($this, $this->address);

		$this->input = new Threaded();
		$this->output = new Threaded();
		$this->start(PTHREADS_INHERIT_NONE);
		$handler = new PacketHandlerManager($this);

		Bridge::getInstance()->getScheduler()->scheduleRepeatingTask(new ClosureTask(function() use ($handler): void{
			if ($this->state === self::STATE_CONNECTED) return;
			if (is_null($out = $this->output->shift())) return;
			($ev = new CloudPacketResponseEvent($this, json_decode($out, true)))->call();
			if ($ev->isCancelled()) return;
			if (!isset($data["type"])) return;
			try {
				$packet = PacketPool::getInstance()->getPacket($data["type"]);
			} catch (Exception $e) {
				$this->getLogger()->error("Unknown packet type: " . $data["type"]);
				return;
			}
			PacketHandlerManager::getInstance()->handlePacket($packet);
		}), 1);
	}

	/**
	 * Function getThreadName
	 * @return string
	 */
	public function getThreadName(): string{
		return "Connection";
	}

	/**
	 * Function onRun
	 * @return void
	 * @throws Exception
	 */
	protected function onRun(): void{
		$this->registerClassLoaders();
		gc_enable();
		error_reporting(-1);
		ini_set("display_errors", "1");
		ini_set("display_startup_errors", "1");

		register_shutdown_function([$this, "shutdown"]);
		// set_error_handler([$this, "errorHandler"], E_ALL);

		$this->state = self::STATE_CONNECTING;
		$this->logger->debug("Connecting to cloud..");

		if (!$this->cloudSocket->connect()) {
			$this->state = self::STATE_DISCONNECTED;
			return;
		}
		// socket_getpeername($this->socket, $address, $port);
		$this->state = self::STATE_CONNECTED;
		$this->closeIfNoAcceptedFromCloud = time() +10;
		$this->operate();
	}

	/**
	 * Function quit
	 * @return void
	 */
	public function quit(): void{
		$this->close();
		parent::quit();
	}

	/**
	 * Function operate
	 * @return void
	 * @throws Exception
	 */
	private function operate(): void{
		while ($this->state !== self::STATE_DISCONNECTED) {
			$start = microtime(true);
			$this->onTick();
			$time = microtime(true);
			if (($diff = $time -$start) < 0.02) time_sleep_until($time +0.025 -$diff);
		}
		$this->onTick();
		$this->shutdown();
	}

	/**
	 * Function onTick
	 * @return void
	 * @throws Exception
	 */
	private function onTick(): void{
		$error = socket_last_error();
		socket_clear_error($this->socket);

		if ($error === 10057 || $error === 10055 || $error === 10053) {
			error:
			$this->getLogger()->error("Connection with Cloud-Server has disconnected unexpectedly!");
			$this->shutdown();
			return;
		}
		if ($this->closeIfNoAcceptedFromCloud !== 0 && $this->closeIfNoAcceptedFromCloud < time()) {
			$this->getLogger()->alert("Cloud-Server is  not answering!");
			$this->shutdown();
			return;
		}

		$data = @socket_read($this->socket, 65536);
		if ($data != "") {
			$this->getLogger()->notice($data);
			$this->buffer .= $data;
		}
		if (($packet = $this->outputRead()) !== null && $packet !== "") {
			if (@socket_write($this->socket, $packet) === false) goto error;
		}
		$this->readBuffer();
	}

	/**
	 * Function shutdown
	 * @return void
	 */
	public function shutdown(): void{
		if ($this->state === self::STATE_SHUTDOWN) return;
		$this->state = self::STATE_SHUTDOWN;
		$this->cloudSocket->close();
	}

	/**
	 * Function close
	 * @return void
	 */
	public function close(): void{
		if ($this->state === self::STATE_DISCONNECTED) return;
		$this->state = self::STATE_DISCONNECTED;
		$this->cloudSocket->close();
	}

	/**
	 * Function getLogger
	 * @return PrefixedLogger
	 */
	public function getLogger(): PrefixedLogger{
		return $this->logger;
	}

	/**
	 * Function isClosed
	 * @return bool
	 */
	public function isClosed(): bool{
		return $this->state === self::STATE_DISCONNECTED;
	}

	/**
	 * Function getState
	 * @return int
	 */
	public function getState(): int{
		return $this->state;
	}

	/**
	 * Function setState
	 * @param int $state
	 * @return void
	 */
	public function setState(int $state): void{
		$this->state = $state;
	}

	/**
	 * Function getSocket
	 * @return ?\Socket
	 */
	public function getSocket(): ?\Socket{
		return $this->socket;
	}

	/**
	 * Function setSocket
	 * @param null|\Socket $socket
	 * @return void
	 */
	public function setSocket(?\Socket $socket = null): void{
		$this->socket = $socket;
	}

	/**
	 * Function inputRead
	 * @return null|string
	 */
	public function inputRead(): ?string{
		return $this->input->shift();
	}

	/**
	 * Function inputWrite
	 * @param string $string
	 * @return void
	 */
	public function inputWrite(string $string): void{
		$this->input[] = $string;
	}

	/**
	 * Function outputRead
	 * @return null|string
	 */
	public function outputRead(): ?string{
		return $this->output->shift();
	}

	/**
	 * Function outputWrite
	 * @param string $string
	 * @return void
	 */
	public function outputWrite(string $string): void{
		$this->output[] = $string;
	}

	/**
	 * Function getCloudSocket
	 * @return Socket
	 */
	public function getCloudSocket(): Socket{
		return $this->cloudSocket;
	}

	/**
	 * Function readBuffer
	 * @return void
	 * @throws Exception
	 */
	private function readBuffer(): void{
		if (!empty($this->buffer)) return;

		$offset = 0;
		$len = strlen($this->buffer);

		while ($offset < $len) {
			if ($offset > ($len -6)) break; // NOTE: Tried to decode invalid buffer.
			$length = Binary::readInt(substr($this->buffer, $offset, 4));
			$offset += 4;

			if (($offset +$length) > $len) {
				$offset -= 2; // NOTE: Received incomplete packet
				break;
			}
			$payload = substr($this->buffer, $offset, $length);
			$offset += $length;
			$this->inputWrite($payload);
		}
		if ($offset < $len) $this->buffer = substr($this->buffer, $offset);
		else $this->buffer = "";
	}

	public function writeBuffer(string $payload): void{
		$this->outputWrite($payload);
	}

	public function sendPacket(CloudPacket $packet): void{
		$this->writeBuffer(json_encode($packet->jsonSerialize()));
	}

	private function verifyHeader(string $buffer, int $len, int $offset): int{
		if (($offset +2) > $len) return 0; // NOTE: No Packet-Id + Response info
		$index = 1; // NOTE: Packet-Id
		$supportsResponse = Binary::readBool($buffer[$offset +$index++]);
		if ($supportsResponse) $index = 4; // NOTE: skip Response-Id
		return $index;
	}
}
