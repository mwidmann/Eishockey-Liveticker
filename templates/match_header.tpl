		<table class="match">
			<tr>
				<td class="logo">
					<img src="{$match->home}" alt="{$match->home_name}" class="logoimg" />
				</td>
				<td>
		            <table class="resulttable">
        		        <tr>
		                    <td class="result" rowspan="2" valign="middle">
								<div class="teamname" style="display:none">{$match->home_name}</div>
								{$match->homegoals
							}</td>
                		    <td class="time">{$match->playtime}</td>
		                    <td class="result" rowspan="2" valign="middle">
								<div class="teamname" style="display:none">{$match->away_name}</div>
								{$match->awaygoals}
							</td>
		                </tr>
        		        <tr>
		                    <td class="period">
		                    	{if $match->running eq '1'}		                    
		                    		{$current_period}
		                    	{else}
		                    		abgeschlossen
		                    	{/if}
								{if $match->spectators > 0}
									<div class="spectators">{$match->spectators} Zuseher</div>
								{/if}
		                    </td>
		                </tr>
		            </table>
				</td>
				<td class="logo">
					<img src="{$match->away}" alt="{$match->away_name}" class="logoimg" />
				</td>
			</tr>
		</table>
