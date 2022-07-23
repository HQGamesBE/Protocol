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
use HQGames\network\protocol\UnknownPacket;


/**
 * Interface IPacketHandler
 * @package HQGames\network
 * @author Jan Sohn / xxAROX
 * @date 23. July, 2022 - 16:00
 * @ide PhpStorm
 * @project Bridge
 */
interface IPacketHandler{
	/**
	 * Function handleUnknownPacket
	 * @param UnknownPacket $packet
	 * @return void
	 */
	public function handleUnknownPacket(UnknownPacket $packet): void;

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

	public function handlePingPacket(PingPacket $packet): void;
}
