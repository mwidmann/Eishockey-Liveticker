<?php
if ( !defined( 'CLOCK_RUNNING_DIRECTION' ) )
	define( 'CLOCK_RUNNING_DIRECTION', 'asc' );


// don't change the following settings
define( 'TYPE_INFO', 0 );
define( 'TYPE_GOAL', 1 );
define( 'TYPE_PENALTY', 2 );

define( 'TEAM_HOME', 0 );
define( 'TEAM_AWAY', 1 );
define( 'TEAM_NONE', 2 );

define( 'GOAL_EQ', 0 );
define( 'GOAL_PP1', 1 );
define( 'GOAL_PP2', 2 );
define( 'GOAL_SH1', 3 );
define( 'GOAL_SH2', 4 );
define( 'GOAL_PS', 5 ); // not yet implemented
define( 'GOAL_EN', 6 ); // not yet implemented

define( 'PENALTY_EXT_NONE', 0 );
define( 'PENALTY_EXT_PLUS2', 1 );
define( 'PENALTY_EXT_MISC', 2 );
define( 'PENALTY_EXT_GA_MI', 3 );
define( 'PENALTY_EXT_MATCH', 4 );