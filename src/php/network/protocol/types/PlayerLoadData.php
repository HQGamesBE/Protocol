<?php
/*
 * Copyright (c) Jan Sohn / xxAROX
 * All rights reserved.
 * I don't want anyone to use my source code without permission.
 */

declare(strict_types=1);
namespace HQGames\network\protocol\types;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;


/**
 * Class PlayerLoadData
 * @package HQGames\network\protocol\types
 * @author Jan Sohn / xxAROX
 * @date 24. July, 2022 - 01:26
 * @ide PhpStorm
 * @project Protocol
 */
class PlayerLoadData implements \JsonSerializable{

	public string $identifier;
		public int $coins;
		public int $default_coins;
		public int $last_seen;
		public int $online_time;
		public string $group;
		public int $group_duration;
		public string $language = "eng";
		public string $group_before = "Default";
		public bool $nicked = false;
		public ?string $nickname = null;
		public ?string $nicked_group = null;
		public ?string $nicked_skin = null;
		public bool $vanish = false;
		public bool $fly = false;
		public bool $hunger = false;
		public int $health = 20;
		public array $permissions = [];
		public array $player_settings = [];
		public array $locker = [];
		public ?string $online = null;

	/**
	 * @generate-build-method
	 * Function build
	 * @var string $identifier
	 * @var int $coins
	 * @var int $default_coins
	 * @var int $last_seen
	 * @var int $online_time
	 * @var string $group
	 * @var int $group_duration
	 * @var string $language
	 * @var string $group_before
	 * @var bool $nicked
	 * @var ?string $nickname
	 * @var ?string $nicked_group
	 * @var ?string $nicked_skin
	 * @var bool $vanish
	 * @var bool $fly
	 * @var bool $hunger
	 * @var int $health
	 * @var array $permissions
	 * @var array $player_settings
	 * @var array $locker
	 * @var ?string $online
	 * @return PlayerLoadData
	 */
	#[Pure] public static function build(
		string $identifier,
		int $coins,
		int $default_coins,
		int $last_seen,
		int $online_time,
		string $group,
		int $group_duration,
		string $language,
		string $group_before,
		bool $nicked,
		?string $nickname,
		?string $nicked_group,
		?string $nicked_skin,
		bool $vanish,
		bool $fly,
		bool $hunger,
		int $health,
		array $permissions,
		array $player_settings,
		array $locker,
		?string $online,
	): PlayerLoadData{
		$result = new self;
		$result->identifier = $identifier;
		$result->coins = $coins;
		$result->default_coins = $default_coins;
		$result->last_seen = $last_seen;
		$result->online_time = $online_time;
		$result->group = $group;
		$result->group_duration = $group_duration;
		$result->language = $language;
		$result->group_before = $group_before;
		$result->nicked = $nicked;
		$result->nickname = $nickname;
		$result->nicked_group = $nicked_group;
		$result->nicked_skin = $nicked_skin;
		$result->vanish = $vanish;
		$result->fly = $fly;
		$result->hunger = $hunger;
		$result->health = $health;
		$result->permissions = $permissions;
		$result->player_settings = $player_settings;
		$result->locker = $locker;
		$result->online = $online;
		return $result;
	}

	private function __construct(){
	}

	/**
	 * Function fromArray
	 * @param mixed $playerLoadData
	 * @return PlayerLoadData
	 */
	#[Pure] public static function fromArray(mixed $playerLoadData): PlayerLoadData{
		$identifier = (string)$playerLoadData["identifier"];
		$coins = (int)$playerLoadData["coins"];
		$default_coins = (int)$playerLoadData["default_coins"];
		$last_seen = (int)$playerLoadData["last_seen"];
		$online_time = (int)$playerLoadData["online_time"];
		$group = (string)$playerLoadData["group"];
		$group_duration = (int)$playerLoadData["group_duration"];
		$language = (string)$playerLoadData["language"];
		$group_before = (string)$playerLoadData["group_before"];
		$nicked = (bool)$playerLoadData["nicked"];
		$nickname = (string)$playerLoadData["nickname"];
		$nicked_group = (string)$playerLoadData["nicked_group"];
		$nicked_skin = (string)$playerLoadData["nicked_skin"];
		$vanish = (bool)$playerLoadData["vanish"];
		$fly = (bool)$playerLoadData["fly"];
		$hunger = (bool)$playerLoadData["hunger"];
		$health = (int)$playerLoadData["health"];
		$permissions = (array)$playerLoadData["permissions"];
		$player_settings = (array)$playerLoadData["player_settings"];
		$locker = (array)$playerLoadData["locker"];
		$online = (string)$playerLoadData["online"];

		return self::build(
			$identifier,
			$coins,
			$default_coins,
			$last_seen,
			$online_time,
			$group,
			$group_duration,
			$language,
			$group_before,
			$nicked,
			$nickname,
			$nicked_group,
			$nicked_skin,
			$vanish,
			$fly,
			$hunger,
			$health,
			$permissions,
			$player_settings,
			$locker,
			$online
		);
	}

	#[ArrayShape([
		"identifier"  => "string",
		"online"  => "null|string",
		"coins"  => "int",
		"default_coins"  => "int",
		"last_seen"  => "int",
		"online_time"  => "int",
		"language"  => "string",
		"group"  => "string",
		"group_duration"  => "int",
		"group_before"  => "string",
		"nicked" => "bool",
		"nickname" => "null|string",
		"nicked_group" => "null|string",
		"nicked_skin" => "null|string",
		"vanish" => "bool",
		"fly"    => "bool",
		"hunger" => "bool",
		"health" => "int",
		"permissions" => "array",
		"player_settings" => "array",
		"locker" => "array",
	])] public function jsonSerialize(): array{
		return [
			"identifier"  => $this->identifier,
			"online"  => $this->online,
			"coins"  => $this->coins,
			"default_coins"  => $this->default_coins,
			"last_seen"  => $this->last_seen,
			"online_time"  => $this->online_time,
			"language"  => $this->language,
			"group"  => $this->group,
			"group_duration"  => $this->group_duration,
			"group_before"  => $this->group_before,
			"nicked"  => $this->nicked,
			"nickname"  => $this->nickname,
			"nicked_group"  => $this->nicked_group,
			"nicked_skin"  => $this->nicked_skin,
			"vanish" => $this->vanish,
			"fly"    => $this->fly,
			"hunger" => $this->hunger,
			"health" => $this->health,
			"permissions" => $this->permissions,
			"player_settings" => $this->player_settings,
			"locker" => $this->locker,
		];
	}
}
