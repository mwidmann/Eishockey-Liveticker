<?php

require_once 'entry.class.php';
require_once 'livetickersmarty.class.php';

class MatchView {


	public $lines = array(); // holds the lines
	public $stat = array(); // holds the statistical data 

	public $id;
	public $home;
	public $home_name;
	public $away;
	public $away_name;
	public $matchdate;
	public $info;
	public $running;
	public $overtime_length;
	public $spectators;
	public $head1;
	public $head2;
	public $linesman1;
	public $linesman2;

	public $playtime;
	
	public $homegoals = 0;
	public $awaygoals = 0;

	var $conn;


	/**
	* Creates a new MatchView loading the data corresponding to the match
	* with the provided $id
	* @param int $id the id of the match to load
	* @param DBLayer $conn the database connection object
	* @throws Exception
	*/
	public function MatchView($conn, $id) {
	
		$this->init();
		
		$this->id = $id;
		$this->conn = $conn;
	
		$sql = "SELECT * FROM liveticker_match WHERE match_id = $id";

		if ($conn->query($sql) > 0) {
			$row = $conn->resultRows[0];
			$this->home				= $row->home;
			$this->home_name		= $row->home_name;
			$this->away				= $row->away;
			$this->away_name		= $row->away_name;
			$this->matchdate		= $row->matchdate;
			$this->info				= $conn->escape($row->info);
			$this->running			= $row->running;
			$this->overtime_length 	= $row->overtime_length;
			$this->spectators 		= $row->spectators;
			$this->head1			= $row->head1;
			$this->head2			= $row->head2;
			$this->linesman1		= $row->linesman1;
			$this->linesman2		= $row->linesman2;
		} else {
			$conn->finalize();
			throw new Exception("Match with $id doesn't exist");
		}
	}
	
	/**
	* Loads the entries of the ticker, creates the required Objects and
	* stores them into the $lines array.
	*/
	public function loadEntries() {
		
		$homegoals = 0; $awaygoals = 0;
		
		$sql = "SELECT *, UNIX_TIMESTAMP(entry_time) AS u_entry_time 
				FROM liveticker_entry 
				WHERE match_id = $this->id
				ORDER BY playtime ASC, entry_id ASC";
				
		if ($this->conn->query($sql) > 0) {
			for ($i = 0; $i < $this->conn->resultCount; $i++) {
		
				$row = $this->conn->resultRows[$i];
				
				$entry_type			= $row->entry_type;
				$team				= $row->team;
				$playtime			= $row->playtime;
				$player1			= $row->player1;
				$player2			= $row->player2;
				$player3			= $row->player3;
				$powerplay			= $row->powerplay;
				$penalty_time		= $row->penalty_time;
				$penalty_reason		= $row->penalty_reason;
				$penalty_extension	= $row->penalty_extension;
				$info				= $this->conn->unescape($row->info);
				$entry_time			= $row->u_entry_time;
				
				$theteam = "";
				if ($team == 0) $theteam = $this->home_name;
				if ($team == 1) $theteam = $this->away_name;
				
				$current_standing = $this->homegoals . ":" . $this->awaygoals;
				$this->playtime = $playtime;
				
				$entry = null;
				
				switch ($entry_type) {
				
					case 0: // info
						$entry = new Info($entry_time, $playtime, $theteam, $current_standing, $info);
						break;
					case 1: // goal
						$this->addGoal($playtime, $team);
						if ($team == 0) $this->homegoals++;
						if ($team == 1) $this->awaygoals++;
						
						$entry = new Goal($entry_time, $playtime, $theteam, $this->homegoals . ":" . $this->awaygoals, $player1, $player2, $player3, $powerplay, $info);
						break;
					case 2: // penalty
						$this->addPenalty($playtime, $team, $penalty_time + $this->getExtPenalty($penalty_extension));
					
						$entry = new Penalty($entry_time, $playtime, $theteam, $current_standing, $player1, $penalty_time, $this->getExtPenalty($penalty_extension), $penalty_reason, $info);
						break;
				}
				array_push($this->lines, $entry);
			}
		}				
	}

	/**
	* Passes the data to the Smarty template engine and displays the results.
	* The match.tpl is the only templace called directly, the other ones are
	* included 
	* @return string the generated output from Smarty
	*/
	public function toString() {
	
		$period = '';		
		switch ($this->getPeriod($this->playtime,$this->overtime_length)) {
			case 1: $period = "1.Drittel";break;
			case 2: $period = "2.Drittel";break;
			case 3: $period = "3.Drittel";break;
			case 4: if ($this->homegoals == $this->awaygoals) { $period = "Overtime"; } else { $period = "Nach dem Spiel"; } break;							   
			case 5: $period = "Drittelpause";break;
			case 6: $period = "Drittelpause";break;
			case 7: if ($this->homegoals == $this->awaygoals) { $period = "Vor der Overtime"; } else { $period = "Nach dem Spiel"; } break;
			case 8: $period = "Vor dem Spiel"; break;
			case 9: if ($this->homegoals == $this->awaygoals) { $period = "Penaltyschiessen"; } else { $period = "Nach dem Spiel"; } break;
		}			
				
		$templateEngine = new LivetickerSmarty();
		
		$templateEngine->assign('match', $this);
		$templateEngine->assign('current_period', $period);
		$templateEngine->assign('_smarty_debug_output', 'html');
		return $templateEngine->fetch('match.tpl');
	}

	/**
	* Initializes the statistics array to avoid notices in the log
	* files.
	*/	
	private function init() {
		for ($i = 1; $i < 5; $i++) {
			$this->stat["g" . $i] = array(0, 0);
			$this->stat["p" . $i] = array(0, 0);
		}
	}

	/**
	* Adds a goal to the statiscts array in order to have an efficient and easy
	* way to calculate the goals per period.
	* @param string $playtime as stored in the database.
	* @param int $team the team who scored.
	*/
	private function addGoal($playtime, $team) {
		$this->stat["g" . $this->getPeriod($playtime)][$team]++;
	}
	
	/**
	* Adds a penalty to the statistics array in order to have an efficient and
	* easy way to calculate the penalties per period.
	* @param string $playtime as stored in the database.
	* @param int $team the team who got the penalty.
	* @param int $penalty_time the penalty in minutes (normal + extension)
	*/
	private function addPenalty($playtime, $team, $penalty_time) {
		$this->stat["p" . $this->getPeriod($playtime)][$team] += $penalty_time;
	}
	
	/**
	* Calculates which period the given $playtime belongs to. It's also 
	* capable of recognising before and after the game as well as pauses.
	* @param string $playtime as stored in the database
	*/
	private function getPeriod( $playtime ) {

		if ( NULL == $playtime )
			return 8;
		
		list ($min, $sec) = split(":", $playtime);
		
		if ($min == 0 && $sec == 0) {
			return 8;
		} else if ($min < 20) {
			return 1;
		} else if ($min == 20 && $sec == 0) {
			return 5;
		} else if ($min < 40) {
			return 2;
		} else if ($min == 40 && $sec == 0) {
			return 6;
		} else if ($min < 60) { 
			return 3;
		} else if ($min == 60 && $sec == 0) {
			return 7;
		} else if (($min == 60 && $sec > 0) || ($min < (60 + $this->overtime_length))) {
			return 4;
		} else if ($min == (60 + $this->overtime_length)) {
			return 9;
		}
	}
	
	/**
	* Gets the correct amount of extended minutes depending on the 
	* $penalty_extension
	* @param int $penalty_extension the internal penalty extension id
	* @return int the number of minutes
	*/
	private function getExtPenalty($penalty_extension) {
		switch ($penalty_extension) {
			case 0: return 0;
			case 1:	return 2;
			case 2:	return 10;
			case 3:	return 20;
			case 4: return 25;
		}
	}


}