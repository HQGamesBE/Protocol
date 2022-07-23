<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network\types;
/**
 * Class ServerType
 * @package HQGames\network\packet_types
 * @author Jan Sohn / xxAROX
 * @date 11. July, 2022 - 21:28
 * @ide PhpStorm
 * @project Bridge
 */
final class ServerType{
	const lobby     = "Lobby";
	const game      = "Game";
	const builder   = "Builder";
	const developer = "Developer";
}
