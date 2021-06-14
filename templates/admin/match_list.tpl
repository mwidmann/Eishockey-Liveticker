{include file="./admin_header.tpl"}
		<div class="msg">{$msg}</div>
		{if $matches|@count == 0}
			Keine Spiele vorhanden -
		{else}
			{$matches|@count}  Matches gefunden -
		{/if}
		<a href="match_edit.php">neues Match erstellen</a>

		<ul>
		{section name=idx loop=$matches}

			{assign var=entry value=$matches[idx]}
			<li>

				{if $entry->running}
					<span style="color: #009900; font-weight: bold">AKTIV</span>
				{else}
					<span style="color: #990000; font-weight: bold">INAKTIV</span>
				{/if}
				{$entry->matchdate|date_format:"%d.%m.%Y %H:%M"}
				<b>{$entry->home_name}</b> gg. <b>{$entry->away_name}</b>
				[<a href="match_edit.php?id={$entry->match_id}">editieren</a>] -
				[<a href="liveticker.php?id={$entry->match_id}&home_name={$entry->home_name}&away_name={$entry->away_name}">liveticker</a>] -
				[<a href="../?id={$entry->match_id}" target="_new">öffentliche URL zum Liveticker</a>]

			</li>

		{/section}
		</ul>
	</body>
</html>

{* include file="debug.tpl" *}
