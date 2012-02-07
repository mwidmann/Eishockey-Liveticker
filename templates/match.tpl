<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Eishockey Liveticker {$match->home_name} gegen {$match->away_name} am {$match->matchdate|date_format:"%e.%m.%Y um %H:%M"}</title>
		<meta http-equiv="Content-Type" content="text/xhtml; charset=UTF-8" />
		<meta name="keywords" content="eishockey, hockey, icehockey, liveticker, ticker, live, {$match->home_name}, {$match->away_name}" />
		<meta name="description" content="Eishockey Liveticker des Hockey Spiels {$match->home_name} gegen {$match->away_name}" />
        <link rel="stylesheet" type="text/css" href="styles.css" />
		<link media="handheld, only screen and (max-device-width: 480px)" href="mobile.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript">
		<!--//
		{if $match->running eq '1' }
			setTimeout("window.location.href='{$smarty.server.SCRIPT_NAME}?id={$match->id}'", 30000);
		{/if}
		//-->
		</script>

	</head>
	<body>
		<!-- here comes the header -->
		{include file="match_header.tpl"}
	
		<!-- if you have ads this could be a good place to put them -->
		{include file="ads.tpl"}
	
		<!-- structure of the remaining data -->
		<table width="100%">
			<tr>
				<td align="left" valign="top">
					<!-- the ticker data comes here -->
					<table width="100%" class="ticker">
					{section name=idx loop=$match->lines step=-1}
						
						{assign var=entry value=$match->lines[idx]}

						{if $entry->entry_type eq 'info'}
							{include file="info.tpl"}
						{/if}
						{if $entry->entry_type eq 'goal'}
							{include file="goal.tpl"}
						{/if}
						{if $entry->entry_type eq 'penalty'}
							{include file="penalty.tpl"}
						{/if}
						
					{/section}
					</table>
					
				</td>
				<td width="170" valign="top" aling="left">
					<!-- statistical data comes here -->
					{include file="referees.tpl"}
					{include file="statistics_goal.tpl"}
					{include file="statistics_penalty.tpl"}
				</td>
			</tr>
		</table>
	
	<div class="copy">Liveticker software provided by <a target="_new" href="http://www.widmann.org/">Martin Widmann</a></div>
	{* include file="debug.tpl" *}
	</body>
</html>