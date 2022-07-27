<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network;
use HQGames\network\protocol\ConnectedPacket;
use HQGames\network\protocol\ConnectPacket;
use HQGames\network\protocol\DisconnectPacket;
use HQGames\network\protocol\ErrorPacket;
use HQGames\network\protocol\PingPongPacket;
use HQGames\network\protocol\PlayerLoadPacket;
use HQGames\network\protocol\SaveSkinPacket;
use HQGames\network\protocol\UnknownPacket;
use PrefixedLogger;


/**
 * Interface IProtocolPacketHandler
 * @package HQGames\network
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 16:00
 * @ide PhpStorm
 * @project Bridge
 */
interface IProtocolPacketHandler extends IPacketHandler{
	/**
	 * Function getCloudConnection
	 * @return null|CloudConnection
	 */
	public function getCloudConnection(): ?CloudConnection;

	/**
	 * Function getLogger
	 * @return PrefixedLogger
	 */
	public function getLogger(): PrefixedLogger;

	/**
	 * Function handleUnknownPacket
	 * @param UnknownPacket $packet
	 * @return void
	 */
	public function handleUnknownPacket(UnknownPacket $packet): void;

	/**
	 * Function handleErrorPacket
	 * @param ErrorPacket $packet
	 * @return void
	 */
	public function handleErrorPacket(ErrorPacket $packet): void;

	/**
	 * Function handleConnectPacket
	 * @param ConnectPacket $packet
	 * @return void
	 */
	public function handleConnectPacket(ConnectPacket $packet): void;

	/**
	 * Function handleConnectedPacket
	 * @param ConnectedPacket $packet
	 * @return void
	 */
	public function handleConnectedPacket(ConnectedPacket $packet): void;

	/**
	 * Function handleDisconnectPacket
	 * @param DisconnectPacket $packet
	 * @return void
	 */
	public function handleDisconnectPacket(DisconnectPacket $packet): void;

	/**
	 * Function handlePingPongPacket
	 * @param PingPongPacket $packet
	 * @return void
	 */
	public function handlePingPongPacket(PingPongPacket $packet): void;

	/**
	 * Function handlePlayerLoadPacket
	 * @param PlayerLoadPacket $packet
	 * @return void
	 */
	public function handlePlayerLoadPacket(PlayerLoadPacket $packet): void;

	/**
	 * Function handlePlayerLoadPacket
	 * @param SaveSkinPacket $packet
	 * @return void
	 */
	public function handleSaveSkinPacket(SaveSkinPacket $packet): void;
}
