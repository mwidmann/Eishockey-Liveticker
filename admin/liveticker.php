<?php
/*
  ============================================================================
  this page manages entries to a liveticker.
  ============================================================================
  Author: Martin Widmann, martin.widmann@ideefix.net
  Version: 1.2
 */
require( '../config.php' );
require( '../constants.php' );
require( '../db_functions.php' );
require( '../file_functions.php' );
require( '../match/livetickersmarty.class.php' );
require( '../match/matchview.class.php' );

$entry_id = 0;
$id = ( int ) $_REQUEST[ "id" ];

$entry_id = $showall = 0;
$period = 1;
$action = $home_name = $away_name = $smin = $ssec = "";

if ( isset( $_REQUEST[ "entry_id" ] ) )
	$entry_id = ( int ) $_REQUEST[ "entry_id" ];

if ( isset( $_REQUEST[ "todoaction" ] ) )
	$action = $_REQUEST[ "todoaction" ];

if ( isset( $_REQUEST[ "showall" ] ) )
	$showall = $_REQUEST[ "showall" ];

if ( isset( $_REQUEST[ "home_name" ] ) )
	$home_name = $_REQUEST[ "home_name" ];

if ( isset( $_REQUEST[ "away_name" ] ) )
	$away_name = $_REQUEST[ "away_name" ];

if ( isset( $_REQUEST[ "smin" ] ) )
	$smin = $_REQUEST[ "smin" ];

if ( isset( $_REQUEST[ "ssec" ] ) )
	$ssec = $_REQUEST[ "ssec" ];

if ( isset( $_REQUEST[ "period" ] ) )
	$period = $_REQUEST[ "period" ];


$entry_type = TYPE_INFO;
$team = TEAM_NONE;
$playtime = "00:00";
$playtime_min = ( CLOCK_RUNNING_DIRECTION == 'asc' ) ? "00" : "20"; // the playtime
$playtime_sec = "00";
$player1 = "";   // the 1st player involved.
$player2 = "";   // 1st assist player
$player3 = "";   // 2nd assist player
$powerplay = GOAL_EQ;
$penalty_time = 2;
$penalty_reason = "";
$penalty_extension = PENALTY_EXT_NONE;
$info = "";

if ( $smin )
	$playtime_min = $smin;
if ( $ssec )
	$playtime_sec = $ssec;

$players_home = loadContent( OUTPUTDIR . "/players_home_" . $id );
$players_away = loadContent( OUTPUTDIR . "/players_away_" . $id );

if ( 'store' == $action ) {
	$entry_type = $_POST[ "entry_type" ];
	$team = $_POST[ "team" ];
	$period = $_POST[ "period" ];
	$playtime_min = $_POST[ "playtime_min" ];
	$playtime_sec = $_POST[ "playtime_sec" ];
	$player1 = $conn->escape( $_POST[ "player1" ] );
	$player2 = $conn->escape( $_POST[ "player2" ] );
	$player3 = $conn->escape( $_POST[ "player3" ] );
	$powerplay = $_POST[ "powerplay" ];
	$penalty_time = $_POST[ "penalty_time" ];
	$penalty_reason = $conn->escape( $_POST[ "penalty_reason" ] );
	$penalty_extension = $_POST[ "penalty_extension" ];
	$info = $conn->escape( $_POST[ "info" ] );

	$smin = $playtime_min;
	$ssec = $playtime_sec;

	if ( CLOCK_RUNNING_DIRECTION == 'asc' ) {
		// check if the minutes display is < 20. if so add (period - 1) * 20
		if ( $playtime_min < 20 )
			$playtime_min = $playtime_min + ( $period - 1 ) * 20;
	} else {

		$playtime_sec = 60 - $playtime_sec;
		if ( 60 == $playtime_sec ) $playtime_sec = 0;
		
		$detract = 1;
		if ( 0 == $playtime_sec ) $detract = 0;

		if ( $period < 4 ) {
			$playtime_min = ( $period * 20 - $detract - $playtime_min );
		} else {
			$matchView = new MatchView( $conn, $id );
			$playtime_min = ( 60 + $matchView->overtime_length - $detract - $playtime_min );
		}

	}

	$playtime = sprintf( '%02d', $playtime_min ) . ":" . sprintf( '%02d', $playtime_sec );

	if ( 0 == $entry_id ) {

		// create a new entry
		$sql =
				"INSERT INTO	liveticker_entry " .
				"				(" .
				"				match_id, entry_type, team, playtime, player1, " .
				"				player2, player3, powerplay, penalty_time, " .
				"				penalty_reason, penalty_extension, info, entry_time" .
				"				) " .
				"VALUES			($id, $entry_type, $team, " .
				"				'$playtime', " .
				"				'$player1', '$player2', '$player3', $powerplay, " .
				"				$penalty_time, '$penalty_reason', " .
				"				$penalty_extension, '$info', now())";

		$conn->query( $sql );
		//$smin = $playtime_min;
		//$ssec = $playtime_sec;
	} else {

		// update a given entry
		$sql =
				"UPDATE			liveticker_entry " .
				"SET			entry_type = $entry_type, " .
				"				team = $team, " .
				"				playtime = '$playtime', " .
				"				player1 = '$player1', " .
				"				player2 = '$player2', " .
				"				player3 = '$player3', " .
				"				powerplay = $powerplay, " .
				"				penalty_time = $penalty_time, " .
				"				penalty_reason = '$penalty_reason', " .
				"				penalty_extension = $penalty_extension, " .
				"				info = '$info' " .
				"WHERE			entry_id = $entry_id";

		$conn->query( $sql );
	}

	callForUpdate( $id );
	$conn->finalize();

	// redirect to self
	$host = $_SERVER[ 'HTTP_HOST' ];
	$uri = rtrim( dirname( $_SERVER[ 'PHP_SELF' ] ), '/\\' );
	$extra = 'liveticker.php?id=' . $id . '&home_name=' . $home_name . '&away_name=' . $away_name . '&smin=' . $smin . '&ssec=' . $ssec . '&period=' . $period;
	header( "Location: http://$host$uri/$extra" );
	exit;
} else if ( 'edit' == $action ) {

	// load an entry
	$sql =
			"SELECT		* " .
			"FROM		liveticker_entry " .
			"WHERE		entry_id = $entry_id";

	$conn->query( $sql );
	if ( $conn->query( $sql ) > 0 ) {

		$row = $conn->resultRows[ 0 ];
		$entry_type = $row->entry_type;
		$team = $row->team;
		$playtime = $row->playtime;
		$player1 = $conn->unescape( $row->player1 );
		$player2 = $conn->unescape( $row->player2 );
		$player3 = $conn->unescape( $row->player3 );
		$powerplay = $row->powerplay;
		$penalty_time = $row->penalty_time;
		$penalty_reason = $conn->unescape( $row->penalty_reason );
		$penalty_extension = $row->penalty_extension;
		$info = $conn->unescape( $row->info );

		list ($playtime_min, $playtime_sec, $period) = calculatePlaytimeAndPeriod( $playtime, $conn, $id );
	}
} else if ( 'delete' == $action ) {

	// delete an entry
	$sql = "DELETE FROM liveticker_entry WHERE entry_id = $entry_id";

	$conn->query( $sql );
	$entry_id = 0;
	callForUpdate( $id );
}

$templateEngine = new LivetickerSmarty();

$templateEngine->assign( "id", $id );
$templateEngine->assign( "entry_id", $entry_id );
$templateEngine->assign( "home_name", $home_name );
$templateEngine->assign( "away_name", $away_name );
$templateEngine->assign( "entry_type", $entry_type );
$templateEngine->assign( "team", $team );
$templateEngine->assign( "playtime", $playtime );
$templateEngine->assign( "player1", $player1 );
$templateEngine->assign( "player2", $player2 );
$templateEngine->assign( "player3", $player3 );
$templateEngine->assign( "powerplay", $powerplay );
$templateEngine->assign( "penalty_time", $penalty_time );
$templateEngine->assign( "penalty_reason", $penalty_reason );
$templateEngine->assign( "penalty_extension", $penalty_extension );
$templateEngine->assign( "info", $info );
$templateEngine->assign( "playtime_min", $playtime_min );
$templateEngine->assign( "playtime_sec", $playtime_sec );
$templateEngine->assign( "period", $period );
$templateEngine->assign( "players_home", preg_split( '/[\n\r]+/', $players_home ) );
$templateEngine->assign( "players_away", preg_split( '/[\n\r]+/', $players_away ) );
$templateEngine->assign( "showall", $showall );
$templateEngine->assign( "smin", $smin );
$templateEngine->assign( "ssec", $ssec );

$sql =	"SELECT		entry_id, entry_type, playtime, player1, left(info, 50) AS shortinfo " .
		"FROM		liveticker_entry " .
		"WHERE		match_id = $id " .
		"ORDER BY	entry_id DESC ";
if ( $showall != "1" )
	$sql .= "LIMIT 10";

$conn->query( $sql );
$templateEngine->assign( "entries", $conn->resultRows );

$templateEngine->assign( 'title', 'Liveticker verwalten' );
$templateEngine->assign( '_smarty_debug_output', 'html' );
$conn->finalize();
echo $templateEngine->fetch( FRAGMENTDIR . 'admin/ticker_edit.tpl' );

$conn->finalize();

function normalize( $text ) {
	if ( strlen( $text ) == 1 ) {
		$text = "0" . $text;
	}
	if ( is_numeric( $text ) ) {
		return $text;
	} else {
		return "00";
	}
}

function callForUpdate( $id ) {
	touch( OUTPUTDIR . "/update_$id" );
}

function playerListAsOptions( $list, $selectedPlayer ) {
	$ret = "";
	$players = preg_split( '/[\n\r]+/', $list );
	for ( $i = 0; $i < count( $players ); $i++ ) {
		list($currplayer, $l) = split( ",", $players[ $i ] );
		$currplayer = trim( $currplayer );
		$ret .= "<option value=\"" . $currplayer . "\"";
		if ( $currplayer == $selectedPlayer )
			$ret .= " selected";
		$ret .= ">" . $currplayer . "</option>\n";
	}
	return $ret;
}

function calculatePlaytimeAndPeriod( $time, $conn, $id ) {
	if ( NULL == $time )
		return array( "00", "00", 1 );

	list ( $min, $sec ) = split( ":", $time );

	$period = 1;

	if ( $min == 0 && $sec == 0 || $min < 20 ) {
		$period = 1;
	} else if ( $min == 20 && $sec == 0 || $min < 40 ) {
		$period = 2;
	} else if ( $min == 40 && $sec == 0 || $min < 60 ) {
		$period = 3;
	} else if ( $min == 60 && $sec == 0 || $min > 60 ) {
		$period = 4;
	}

	if ( CLOCK_RUNNING_DIRECTION == 'asc' ) {
		$min = ( $min - ( $period - 1 ) * 20 );
 	} else {
		$sec = 60 - $sec;
		if ( 60 == $sec ) $sec = 0;

		$detract = 1;
		if ( 0 == $sec ) $detract = 0;

		if ( $period < 4 ) {
			$min = ( $period * 20 - $detract - $min );
		} else {
			$matchView = new MatchView( $conn, $id );
			$min = ( 60 + $matchView->overtime_length - $min );
		}
	}

	return array( sprintf( '%02d', $min ), sprintf( '%02d', $sec ), $period );
}
?>

