<?php

/**
* Abstract class defining what a liveticker entry can look like. Don't use
* this class directly, use either Info, Goal or Penalty.
*/
class Entry {
	
	public $entry_type;
	public $playtime;
	public $team;
	public $current_standing;
	public $player1;
	public $player2;
	public $player3;
	public $powerplay;
	public $penalty_time;
	public $penalty_reason;
	public $penalty_extension;
	public $info;
	public $entry_time;
	
	public $specials = array();

	protected function setInfo($info) {
		$this->info = preg_replace('@http://(([^\s0-9-\.][a-zA-Z0-9-\.]+)\S+)@','<a href="http://\\1" target="_blank">http://\\1</a>',$info);
		$this->info = eregi_replace("\n", "<br>", $this->info);
	}

	/**
	* Adds a special template replacement for the current entry.
	* @param string $key the key of the replacement
	* @param strnng $value the value of the replacement
	*/
	public function addSpecial($key, $value) {
		$this->specials[$key] = $value;
	}
	
}

/**
* The Info class holds the most basic liveticker object, the info
* object.
* @package match
*/
class Info extends Entry {

	public function Info($entry_time, $playtime, $team, $current_standing, $info) {
		$this->entry_type = "info";
		$this->entry_time = $entry_time;
		$this->playtime = $playtime;
		$this->team = $team;
		$this->current_standing = $current_standing;
		$this->setInfo($info);
	}
}

/**
* The Goal class holds the data related to a goal event in the liveticker
* @package match
*/
class Goal extends Entry {

	public function Goal($entry_time, $playtime, $team, $current_standing, $player, $assist1, $assist2, $powerplay, $info) {
		$this->entry_type = "goal";
		$this->entry_time = $entry_time;
		$this->playtime = $playtime;
		$this->team = $team;
		$this->current_standing = $current_standing;
		$this->player1 = $player;
		$this->player2 = $assist1;
		$this->player3 = $assist2;
		$this->powerplay = $powerplay;
		$this->setInfo($info);
		
		switch ($powerplay) {
			case GOAL_EQ: $pp_value = "EQ"; break;
			case GOAL_PP1: $pp_value = "PP1"; break;
			case GOAL_PP2: $pp_value = "PP2"; break;
			case GOAL_SH1: $pp_value = "SH1"; break;
			case GOAL_SH2: $pp_value = "SH2"; break;
			case GOAL_PS: $pp_value = "PS"; break;
			case GOAL_EN: $pp_value = "EN"; break;
		}
		
		$this->addSpecial('powerplay', $pp_value);
		
		$assists = "";
		if ($assist1 != '') {
			$assists = "($assist1";
			if ($assist2 != '') {
				$assists .= ", $assist2";
			}
			$assists .= ")";
		}
		$this->addSpecial('assists', $assists);
		
	}
}

/**
* The Penalty class holds the data related to a penalty event in the liveticker
* @package match
*/
class Penalty extends Entry {

	public function Penalty($entry_time, $playtime, $team, $current_standing, $player, $penalty_time, $penalty_extension, $penalty_reason, $info) {
		$this->entry_type = "penalty";
		$this->entry_time = $entry_time;
		$this->playtime = $playtime;
		$this->team = $team;
		$this->current_standing = $current_standing;
		$this->player1 = $player;
		$this->penalty_time = $penalty_time;
		$this->penalty_extension = $penalty_extension;
		$this->penalty_reason = $penalty_reason;
		$this->setInfo($info);
		
		$penalty = "";
		if ($penalty_time > 0 && $penalty_extension == 0) { // normal small penalty
			$penalty = $penalty_time . " Minuten";
		} else if ($penalty_time > 0 && $penalty_extension > 0) { // normal penalty + extension
			switch ($penalty_extension) {
				case 2:
					$penalty = $penalty_time . "+2 Minuten";
					break;
				case 10:
					$penalty = $penalty_time . "+10 Minuten Disziplinarstrafe";
					break;
				case 20:
					$penalty = $penalty_time . " Minuten + Spieldauerdisziplinarstrafe";
					break;
				case 25:
					$penalty = $penalty_time . " Minuten + Matchstrafe";
					break;
			}
		} else if ($penalty_time == 0 && $penalty_extension == 10) { // there aren't other cases
			$penalty = "10 Minuten Disziplinarstrafe";
		}
		
		$this->addSpecial('penalty', $penalty);
	}
}
