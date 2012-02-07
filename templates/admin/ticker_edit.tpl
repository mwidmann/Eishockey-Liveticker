<html>
	<head>
		<title>{$title}</title>
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<meta http-equiv="Content-Type" content="text/xhtml; charset=UTF-8" />

		{literal}
		<script type="text/javascript">
			function switchType( type ) {
				if ( type == 0 ) {
					document.getElementById( "players" ).style.display = 'none';
					document.getElementById( "goal_players" ).style.display = 'none';
					document.getElementById( "goal_type" ).style.display = 'none';
					document.getElementById( "penalty" ).style.display = 'none';
				} else if ( type == 1 ) {
					document.getElementById( "players" ).style.display = 'inline';
					document.getElementById( "goal_players" ).style.display = 'inline';
					document.getElementById( "goal_type" ).style.display = 'inline';
					document.getElementById( "penalty" ).style.display = 'none';
				} else if ( type == 2 ) {
					document.getElementById( "players" ).style.display = 'inline';
					document.getElementById( "goal_players" ).style.display = 'none';
					document.getElementById( "goal_type" ).style.display = 'none';
					document.getElementById( "penalty" ).style.display = 'inline';
				}
			}

			function usePlayer( $obj, $field ) {
				if ( $obj.options[ $obj.selectedIndex ].value != "" )
					document.form1.elements[ $field ].value = $obj.options[ $obj.selectedIndex ].value;
			}

			function checkAndSubmit() {
				var frm = document.form1;
				if ( frm.entry_type[ 1 ].checked || frm.entry_type[ 2 ].checked ) { // we have a goal or a penalty
					if ( frm.team[ 0 ].checked ) { // no team was selected
						alert("Kein Team ausgewählt!");
						return false;
					}
				}
				return true;
			}
		</script>
		{/literal}
	</head>

	<body onLoad="switchType( {$entry_type} );">
		<h1>{$title}</h1>
		<form name="form1" action="liveticker.php" method="post" onsubmit="return checkAndSubmit();">
			<input type="hidden" name="smin" value="{$smin}" />
			<input type="hidden" name="ssec" value="{$ssec}" />
			<input type="hidden" name="home_name" value="{$home_name}" />
			<input type="hidden" name="away_name" value="{$away_name}" />
			<input type="hidden" name="id" value="{$id}" />
			<input type="hidden" name="todoaction" value="store" />
			<input type="hidden" name="entry_id" value="{$entry_id}" />

			Drittel:
			<input type="radio" name="period" value="1"{if $period == 1} checked="checked"{/if} /> 1. Drittel |
			<input type="radio" name="period" value="2"{if $period == 2} checked="checked"{/if} /> 2. Drittel |
			<input type="radio" name="period" value="3"{if $period == 3} checked="checked"{/if} /> 3. Drittel |
			<input type="radio" name="period" value="4"{if $period == 4} checked="checked"{/if} /> OT
			<br/>


			Eintragstyp:
			<input type="radio" name="entry_type" value="0"{if $smarty.const.TYPE_INFO == $entry_type} checked="checked"{/if} onclick="switchType( 0 );" /> INFO |
			<input type="radio" name="entry_type" value="1"{if $smarty.const.TYPE_GOAL == $entry_type} checked="checked"{/if} onclick="switchType( 1 );" /> TOR |
			<input type="radio" name="entry_type" value="2"{if $smarty.const.TYPE_PENALTY == $entry_type} checked="checked"{/if} onclick="switchType( 2 );" /> STRAFE<br />
			Betroffenes Team:
			<input type="radio" name="team" value="2"{if $smarty.const.TEAM_NONE == $team} checked="checked"{/if} /> KEINES/BEIDE |
			<input type="radio" name="team" value="0"{if $smarty.const.TEAM_HOME == $team} checked="checked"{/if} /> HEIM ({$home_name}) |
			<input type="radio" name="team" value="1"{if $smarty.const.TEAM_AWAY == $team} checked="checked"{/if} /> GAST ({$away_name})<br />

	 		Spielzeit:
			<input type="text" name="playtime_min" value="{$playtime_min}" onfocus="this.select()" size="2" />:
	 		<input type="text" name="playtime_sec" value="{$playtime_sec}" onfocus="this.select()" size="2" /><br />

			<span id="players">Involvierte Spieler:<br />
				<input type="text" name="player1" value="{$player1}" /> &lt;-
				<select name="listplayer1" onchange="usePlayer( this, 'player1' );">
					<option value="">{$home_name}</option>
					{section name=idx loop=$players_home}
						{assign var=entry value=$players_home[idx]}
						<option value="{$entry}"{if $entry == $player1} selected="selected"{/if}>{$entry}</option>
					{/section}
				</select> -
				<select name="listplayera1" onchange="usePlayer( this, 'player1' );">
					<option value="">{$away_name}</option>
					{section name=idx loop=$players_away}
						{assign var=entry value=$players_away[idx]}
						<option value="{$entry}"{if $entry == $player1} selected="selected"{/if}>{$entry}</option>
					{/section}
				</select><br/>
				<span id="goal_players">
					Assist 1: <input type="text" name="player2" value="{$player2}" /> &lt;-
					<select name="listplayer2" onchange="usePlayer( this, 'player2' );">
						<option value="">{$home_name}</option>
						{section name=idx loop=$players_home}
							{assign var=entry value=$players_home[idx]}
							<option value="{$entry}"{if $entry == $player2} selected="selected"{/if}>{$entry}</option>
						{/section}
					</select> -
					<select name="listplayera2" onchange="usePlayer( this, 'player2' );">
						<option value="">{$away_name}</option>
						{section name=idx loop=$players_away}
							{assign var=entry value=$players_away[idx]}
							<option value="{$entry}"{if $entry == $player2} selected="selected"{/if}>{$entry}</option>
						{/section}
					</select><br/>
					Assist 2: <input type="text" name="player3" value="{$player3}" /> &lt;-
					<select name="listplayer3" onchange="usePlayer( this, 'player3' );">
						<option value="">{$home_name}</option>
						{section name=idx loop=$players_home}
							{assign var=entry value=$players_home[idx]}
							<option value="{$entry}"{if $entry == $player3} selected="selected"{/if}>{$entry}</option>
						{/section}
					</select> -
					<select name="listplayera3" onchange="usePlayer( this, 'player3' );">
						<option value="">{$away_name}</option>
						{section name=idx loop=$players_away}
							{assign var=entry value=$players_away[idx]}
							<option value="{$entry}"{if $entry == $player3} selected="selected"{/if}>{$entry}</option>
						{/section}
					</select><br/>
				</span><br/>
			</span>
			
			<span id="goal_type">Powerplaytor:
				<input type="radio" name="powerplay" value="4"{if $smarty.const.GOAL_SH2 == $powerplay} checked="checked"{/if} /> SH2 |
				<input type="radio" name="powerplay" value="3"{if $smarty.const.GOAL_SH1 == $powerplay} checked="checked"{/if} /> SH1 |
				<input type="radio" name="powerplay" value="0"{if $smarty.const.GOAL_EQ == $powerplay} checked="checked"{/if} /> EQ |
			 	<input type="radio" name="powerplay" value="1"{if $smarty.const.GOAL_PP1 == $powerplay} checked="checked"{/if} /> PP1 |
				<input type="radio" name="powerplay" value="2"{if $smarty.const.GOAL_PP2 == $powerplay} checked="checked"{/if} /> PP2
			 	<input type="radio" name="powerplay" value="5"{if $smarty.const.GOAL_PS == $powerplay} checked="checked"{/if} /> PS |
				<input type="radio" name="powerplay" value="6"{if $smarty.const.GOAL_EN == $powerplay} checked="checked"{/if} /> EN
				<br/>
			</span>

			<span id="penalty">Strafe:
				<input type="radio" name="penalty_time" value="0"{if 0 == $penalty_time} checked="checked"{/if} /> 0 |
				<input type="radio" name="penalty_time" value="2"{if 2 == $penalty_time} checked="checked"{/if} /> 2 |
				<input type="radio" name="penalty_time" value="5"{if 5 == $penalty_time} checked="checked"{/if} /> 5 Minuten wegen
				<input type="text" name="penalty_reason" value="{$penalty_reason}" size="15" />
				<select name="possible_penalties" onchange="usePlayer( this, 'penalty_reason' );">
					<option value=""></option>
					<option value="Bandencheck">Bandencheck (Boarding)</option>
					<option value="Behinderung">Behinderung (Interference)</option>
					<option value="Beinstellen">Beinstellen (Tripping)</option>
					<option value="Check gegen den Kopf">Check gegen den Kopf (Check to Head or Neck Area)</option>
					<option value="Crosscheck">Crosscheck (Cross-Checking)</option>
					<option value="Check von hinten">Check von hinten (Check from behind)</option>
					<option value="Angriff gegen das Knie">Angriff gegen das Knie (Clipping)</option>
					<option value="Kniecheck">Kniecheck (Kneeing)</option>
					<option value="Ellenbogencheck">Ellenbogencheck (Elbowing)</option>
					<option value="Haken">Haken (Hooking)</option>
					<option value="Halten">Halten (Holding)</option>
					<option value="Faustschlag">Faustschlag (Roughing)</option>
					<option value="Hoher Stock">Hoher Stock (High Sticking)</option>
					<option value="Stockstich">Stockstich (Spearing)</option>
					<option value="Stockschlag">Stockschlag (Slashing)</option>
					<option value="Halten des Stockes">Halten des Stockes (Holding the Stick)</option>
					<option value="Penalty">Penalty (Penalty)</option>
					<option value="zu viele Spieler auf dem Eis">zu viele Spieler auf dem Eis (too many Players on Ice)</option>
					<option value="unsportliches Verhalten">unsportliches Verhalten (unsportsman Like)</option>
					<option value="unerlaubter Körperangriff">unerlaubter Körperangriff (Charging)</option>
					<option value="übertriebene Härte">übertriebene Härte (Roughing)</option>
					<option value="Kritik am Entscheid">Kritik am Entscheid (Misconduct)</option>
					<option value="Spielverzögerung">Spielverzögerung (Delaying the Game)</option>
				</select> - Strafzusatz:

				<select name="penalty_extension" size="1">
					<option value="0"{if $smarty.const.PENALTY_EXT_NONE == $penalty_extension} selected="selected"{/if}></option>
					<option value="1"{if $smarty.const.PENALTY_EXT_PLUS2 == $penalty_extension} selected="selected"{/if}>+2</option>
					<option value="2"{if $smarty.const.PENALTY_EXT_MISC == $penalty_extension} selected="selected"{/if}>MISC</option>
					<option value="3"{if $smarty.const.PENALTY_EXT_GA_MI == $penalty_extension} selected="selected"{/if}>GA-MI</option>
					<option value="4"{if $smarty.const.PENALTY_EXT_MATCH == $penalty_extension} selected="selected"{/if}>MATCH</option>
				</select>
				<br/>
			</span>
																															
			Info:<br />
			<textarea name="info" cols="80" rows="10" wrap="">{$info}</textarea><br/>
			<input type="submit" value="Speichern" />
			<input type="button" value="Abbrechen" onclick="document.location.href='liveticker.php?id={$id}&home_name={$home_name}&away_name={$away_name}&smin={$smin}&ssec={$ssec}&period={$period}'" />
		</form>


		{if $showall == '1'}
		<h1>Alle Einträge zu diesem Spiel (sortiert nach Erstellung)</h1>
		{else}
		<h1>Letzte 10 Einträge zu diesem Spiel - <a href="liveticker.php?id={$id}&showall=1&home_name={$home_name}&away_name={$away_name}&smin={$smin}&ssec={$ssec}&period={$period}">alle zeigen</a></h1>
		{/if}
		<ul>
			{section name=idx loop=$entries}
				{assign var=entry value=$entries[idx]}

				<li>
					{$entry->playtime} -
					
					{if $smarty.const.TYPE_INFO == $entry->entry_type}
					INFO
					{elseif $smarty.const.TYPE_GOAL == $entry->entry_type}
					GOAL
					{elseif $smarty.const.TYPE_PENALTY == $entry->entry_type}
					PENALTY
					{/if}

					{$entry->player1} - {$entry->shortinfo}
					-
					[<a href="liveticker.php?todoaction=edit&entry_id={$entry->entry_id}&id={$id}&home_name={$home_name}&away_name={$away_name}&smin={$smin}&ssec={$ssec}&period={$period}">editieren</a>]
					-
					[<a href="liveticker.php?todoaction=delete&entry_id={$entry->entry_id}&id={$id}&home_name={$home_name}&away_name={$away_name}&smin={$smin}&ssec={$ssec}&period={$period}">löschen</a>]
				</li>

			{/section}
		</ul>
		<hr/>
		[<a href="index.php">Zurück zur Matchauswahl</a>]



	</body>
</html>
{* include file="debug.tpl" *}
