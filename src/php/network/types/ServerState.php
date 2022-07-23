<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network\types;
use pocketmine\utils\EnumTrait;


/**
 * Class ServerState
 * @package HQGames\network\types
 * @author Jan Sohn / xxAROX
 * @date 11. July, 2022 - 21:27
 * @ide PhpStorm
 * @project Bridge
 */
final class ServerState{
	const offline  = "Offline";
	const starting = "Starting";
	const online   = "Online";
}
