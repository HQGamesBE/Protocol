<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */
declare(strict_types=1);
namespace HQGames\network\types;
/**
 * Class GameState
 * @package HQGames\network\types
 * @author Jan Sohn / xxAROX
 * @date 11. July, 2022 - 21:27
 * @ide PhpStorm
 * @project Bridge
 */
final class GameState{
	const lobby    = "Lobby";
	const starting = "Starting";
	const running  = "Running";
	const ending   = "Ending";
}
