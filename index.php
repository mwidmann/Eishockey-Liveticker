<?php

/*
  ============================================================================
  main entry point for normal users. this page decides whether to fetch the
  data from the database or to display the contents of a prerendered file.
  ============================================================================
  Author: Martin Widmann, martin.widmann@ideefix.net
  Version: 1.3.1
 */

require( "config.php" );
require( 'constants.php' );
require( "db_functions.php" );
require( "file_functions.php" );
require( "match/matchview.class.php" );

$id = "";
if ( isset( $_REQUEST[ 'id' ] ) )
	$id = $_REQUEST[ 'id' ];

if ( '' == $id ) {
	// try to find out what the oldest enabled match is. I decided it to be
	// the oldest as some of the users tend to create many games in advance
	// and choosing the newest would most probably create wrong output.
	$sql = "SELECT min(match_id) AS rid FROM liveticker_match WHERE running = 1";
	if ( $conn->query( $sql ) > 0 ) {
		$row = $conn->resultRows[ 0 ];
		$rid = $row->rid;
		if ( $rid != NULL ) {
			$conn->finalize();
			header( "Location: index.php?id=" . $rid );
			exit();
		} else {
			// try to find out the newest disabled match. This could be tricky if there are
			// matches in the future that are disabled, but should work.
			$sql = "SELECT max(match_id) AS mid FROM liveticker_match WHERE running = 0";
			if ( $conn->query( $sql ) > 0 ) {
				$row = $conn->resultRows[ 0 ];
				$mid = $row->mid;
				$conn->finalize();
				if ( $mid != NULL ) {
					header( "Location: index.php?id=" . $mid );
					exit();
				} else {
					echo( "Can't determine a match. Guess there are no." );
					exit();
				}
			} else {
				$conn->finalize();
				echo( "Can't determine a match. Guess there are no." );
				exit();
			}
			echo( "Sorry pal, there are no running matches right now. You'll need a specific ID to access them." );
			exit();
		}
	} else {
		$conn->finalize();
		echo( "Can't determine a currently running match. You'll need a specific ID to access them." );
		exit();
	}
}

$updateFile = OUTPUTDIR . "/update_$id";
$tickerFile = OUTPUTDIR . "/ticker_$id";

$content = "";
// look for the existance of a file called update_$id. if there is none just
// load all the info from the ticker_$id fragment and display what's in there.
// if there have been no changes there's no need to load all the stuff every
// time
if ( file_exists( $updateFile ) ) {

	// delete the update file as only one process should recreate the data
	unlink( $updateFile );


	try {
		$match = new MatchView( $conn, $id );
		$match->loadEntries();
		$content = $match->toString();

		// write the content to file
		writeContent( $tickerFile, $content );
	} catch ( Exception $ex ) { // I assume the match doesn't exist
		$content = "Sorry pal, where'd you get this link from? There's no match!<br/>" . $ex->getMessage();
	}
	$conn->finalize();
} else {
	if ( !file_exists( $tickerFile ) ) {
		echo( "Sorry pal, there's nothing to display for the given Ticker" );
		exit();
	}
	$content = loadContent( $tickerFile );
}

// write the content
echo $content;
?>