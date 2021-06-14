<?php
/*
  ============================================================================
  this page shows all created matches that can be selected and modified at
  wish.
  ============================================================================
  Author: Martin Widmann, martin.widmann@ideefix.net
  Version: 2.0
 */

require( '../config.php' );
require( '../constants.php' );
require( '../db_functions.php' );
require( '../file_functions.php' );
require( '../match/livetickersmarty.class.php' );

$msg = "";
if ( isset( $_REQUEST[ "msg" ] ) )
	$msg = $_REQUEST[ "msg" ];

	// query all existing matches ordered by descending date
	$sql = "select * from liveticker_match order by matchdate desc";
	$conn->query( $sql );

	$matches = $conn->resultRows;
	if ($matches === null) {
		$matches = [];
	}
	$templateEngine = new LivetickerSmarty();
	$templateEngine->assign( 'title', 'Matches verwalten' );
	$templateEngine->assign( 'msg', $msg );
	$templateEngine->assign( 'matches', $matches );
	$templateEngine->assign('_smarty_debug_output', 'html');
	$conn->finalize();
	echo $templateEngine->fetch( FRAGMENTDIR . 'admin/match_list.tpl');
