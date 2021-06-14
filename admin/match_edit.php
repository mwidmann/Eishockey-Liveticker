<?php
/*
  ============================================================================
  this page manages the various matches.
  ============================================================================
  Author: Martin Widmann, martin.widmann@ideefix.net
  Version: 1.2
 */

require( '../config.php' );
require( '../constants.php' );
require( '../db_functions.php' );
require( '../file_functions.php' );
require( '../match/livetickersmarty.class.php' );

$id = $action = "";
if ( isset( $_REQUEST[ "id" ] ) )
	$id = ( int ) $_REQUEST[ "id" ];
if ( isset( $_POST[ "todoaction" ] ) )
	$action = $_POST[ "todoaction" ];

$home = "";
$home_name = "";
$away = "";
$away_name = "";
$matchdate = "";
$info = "";
$players_home = "";
$players_away = "";
$running = "1";
$overtime_length = "5";
$spectators = "";
$head1 = "";
$head2 = "";
$linesman1 = "";
$linesman2 = "";

if ( 'store' == $action ) {
	$home = $_POST[ "home" ];
	$home_name = $_POST[ "home_name" ];
	$away = $_POST[ "away" ];
	$away_name = $_POST[ "away_name" ];
	$matchdate = $_POST[ "matchdate" ];
	$info = $_POST[ "info" ];
	$players_home = $_POST[ "players_home" ];
	$players_away = $_POST[ "players_away" ];
	$running = 0;
	if ( isset( $_POST[ "running" ] ) )
		$running = 1;
	$overtime_length = $_POST[ "overtime_length" ];
	$spectators = 0;
	if ( isset( $_POST[ "spectators" ] ) ) {
		$spectators = intval( $_POST[ "spectators" ] );
	}
	$head1 = $_POST[ "head1" ];
	$head2 = $_POST[ "head2" ];
	$linesman1 = $_POST[ "linesman1" ];
	$linesman2 = $_POST[ "linesman2" ];

	if ( !strtotime( $matchdate ) ) {
		echo("<span class=\"error\">'$matchdate' ist kein gültiges Datum im " .
		"Format 'YYYY-MM-DD HH:mm:ss' (bsp: 2006-10-07 19:30:00)</span>");
	} else {
		$conn->initialize();
		// if $id is not set, insert a new record in the database;
		if ( !$id ) {
			$sql = "INSERT INTO liveticker_match (home, home_name, away, " .
					"away_name, matchdate, info, running, overtime_length, " .
					"spectators, head1, head2, linesman1, linesman2) values (" .
					"'" . $conn->escape( $home ) . "', " .
					"'" . $conn->escape( $home_name ) . "', " .
					"'" . $conn->escape( $away ) . "', " .
					"'" . $conn->escape( $away_name ) . "', " .
					"'" . $conn->escape( $matchdate ) . "', " .
					"'" . $conn->escape( $info ) . "', " .
					"'" . $conn->escape( $running ) . "', " .
					"'" . $conn->escape( $overtime_length ) . "', " .
					"'" . $conn->escape( $spectators ) . "', " .
					"'" . $conn->escape( $head1 ) . "', " .
					"'" . $conn->escape( $head2 ) . "', " .
					"'" . $conn->escape( $linesman1 ) . "', " .
					"'" . $conn->escape( $linesman2 ) . "' " .
					")";
			$conn->query( $sql );
			$id = $conn->lastInsertedId;
		} else {
			$sql = "UPDATE liveticker_match SET " .
					"home = '" . $conn->escape( $home ) . "', " .
					"home_name = '" . $conn->escape( $home_name ) . "', " .
					"away = '" . $conn->escape( $away ) . "', " .
					"away_name = '" . $conn->escape( $away_name ) . "', " .
					"matchdate = '" . $conn->escape( $matchdate ) . "', " .
					"info = '" . $conn->escape( $info ) . "', " .
					"running = '" . $conn->escape( $running ) . "', " .
					"overtime_length = '" . $conn->escape( $overtime_length ) . "', " .
					"spectators = '" . $conn->escape( $spectators ) . "', " .
					"head1 = '" . $conn->escape( $head1 ) . "', " .
					"head2 = '" . $conn->escape( $head2 ) . "', " .
					"linesman1 = '" . $conn->escape( $linesman1 ) . "', " .
					"linesman2 = '" . $conn->escape( $linesman2 ) . "' " .
					"WHERE match_id = " . $id;
			$conn->query( $sql );
		}

		writeContent( OUTPUTDIR . "/players_home_" . $id, $players_home );
		writeContent( OUTPUTDIR . "/players_away_" . $id, $players_away );

		// calling for an update of the ticker
		touch( OUTPUTDIR . "/update_$id" );

		// header( "Location: index.php?msg=Eintrag mit id $id gespeichert." );
	}
} else {

	if ( $id ) {
		$sql = "SELECT * FROM liveticker_match WHERE match_id = $id";

		if ( $conn->query( $sql ) > 0 ) {

			$row = $conn->resultRows[ 0 ];
			$home = $row->home;
			$home_name = $row->home_name;
			$away = $row->away;
			$away_name = $row->away_name;
			$matchdate = $row->matchdate;
			$info = $row->info;
			$running = $row->running;
			$overtime_length = $row->overtime_length;
			$spectators = $row->spectators;
			$head1 = $row->head1;
			$head2 = $row->head2;
			$linesman1 = $row->linesman1;
			$linesman2 = $row->linesman2;

			$players_home = loadContent( OUTPUTDIR . "/players_home_" . $id );
			$players_away = loadContent( OUTPUTDIR . "/players_away_" . $id );
		} else {
			header( "Location: index.php?msg=Eintrag mit id $id existiert nicht." );
		}
	}
}
$conn->finalize();


$templateEngine = new LivetickerSmarty();
$templateEngine->assign( 'title', 'Match bearbeiten' );
$templateEngine->assign( 'id', $id );
$templateEngine->assign( 'home', $home );
$templateEngine->assign( 'home_name', $home_name );
$templateEngine->assign( 'away', $away );
$templateEngine->assign( 'away_name', $away_name );
$templateEngine->assign( 'matchdate', $matchdate );
$templateEngine->assign( 'info', $info );
$templateEngine->assign( 'players_home', $players_home );
$templateEngine->assign( 'players_away', $players_away );
$templateEngine->assign( 'running', $running );
$templateEngine->assign( 'overtime_length', $overtime_length );
$templateEngine->assign( 'spectators', $spectators );
$templateEngine->assign( 'head1', $head1 );
$templateEngine->assign( 'head2', $head2 );
$templateEngine->assign( 'linesman1', $linesman1 );
$templateEngine->assign( 'linesman2', $linesman2 );

$templateEngine->assign( '_smarty_debug_output', 'html' );
echo $templateEngine->fetch( FRAGMENTDIR . 'admin/match_edit.tpl' );
?>
