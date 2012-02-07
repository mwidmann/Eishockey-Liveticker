<tr>
    <td class="localtime">{$entry->entry_time|date_format:"%H:%M"}</td>
    <td class="time">{$entry->playtime}</td>
    <td class="result">{$entry->current_standing}</td>
    <td class="team">{$entry->team}</td>
    <td class="info">
    	<b>{$entry->player1}</b>
    	{if $entry->specials.assists ne ''} {$entry->specials.assists}{/if}
    	- {$entry->specials.powerplay}
    	<br/><br/>{$entry->info}
    </td>
</tr>
<tr>
    <td colspan="5" class="divider"><hr/></td>
</tr>
