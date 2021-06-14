{include file="./admin_header.tpl"}
<form name="form1" action="match_edit.php" method="post">
	<input type="hidden" name="todoaction" value="store">
	<input type="hidden" name="id" value="{$id}">
	Heimteam:<br/><input type="text" name="home_name" value="{$home_name}" /><br/>
	Heimteam Logo URL:<br/><input type="text" name="home" value="{$home}"/><br/>
	Gastteam:<br/><input type="text" name="away_name" value="{$away_name}"/><br/>
	Gastteam Logo URL:<br/><input type="text" name="away" value="{$away}"/><br/>
	Datum des Spiels im Format YYYY-MM-DD HH:mm:ss (Beispiel: 2006-10-07 19:30:00):<br/><input type="text" name="matchdate" value="{$matchdate}"/><br/>
	Zuseher:<br/><input type="text" name="spectators" value="{$spectators}"/><br/>
	Länge der Verlängerung:<br/><input type="text" name="overtime_length" value="{$overtime_length}"/><br/>
	Sonstige Informationen zum Spiel:<br/>
	<textarea name="info" cols="80" rows="10" wrap="">{$info}</textarea><br/>
	Liveticker automatisch aktualisieren: <input type="checkbox" name="running" value="1"{if 1 == $running} checked="checked"{/if}>
	<br/>
	<hr/>
	<h4>Schiedsrichter</h4>
	Head:<br/>
	<input type="text" name="head1" value="{$head1}"/><br/>
	<input type="text" name="head2" value="{$head2}"><br/>

	Linesman:<br/>
	<input type="text" name="linesman1" value="{$linesman1}"><br/>
	<input type="text" name="linesman2" value="{$linesman2}"><br/>

	<hr>
	<table border="0">
		<tr>
			<td colspan="2">Ein Spieler pro Zeile. Die Namen werden genau so ausgegeben, wie
					sie hier eingetragen werden!</td>
		</tr>
		<tr>
			<td>Spieler Heimmannschaft: <br/>
				<textarea name="players_home" cols="40" rows="20" wrap="soft">{$players_home}</textarea>
			</td>
			<td>Spieler Gastmannschaft: <br/>
				<textarea name="players_away" cols="40" rows="20" wrap="soft">{$players_away}</textarea>
			</td>
		</tr>
	</table>
	<input type="submit" value="Speichern"> <input type="button" value="Abbrechen" onclick="history.back();">

</form>

</body>
</html>