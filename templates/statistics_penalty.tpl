<table class="statistics penalties">
	<tr>
		<th colspan="3">Strafstatistik</th>
	</tr>
	<tr>
		<td width="50">Heim</td>
		<td width="50"> </td>
		<td width="50">Gast</td>
	</tr>
	<tr>
		<td>{$match->stat.p1[0]}</td>
		<td>1.Drittel</td>
		<td>{$match->stat.p1[1]}</td>
	</tr>
	<tr>
		<td>{$match->stat.p2[0]}</td>
		<td>2.Drittel</td>
		<td>{$match->stat.p2[1]}</td>
	</tr>
	<tr>
		<td>{$match->stat.p3[0]}</td>
		<td>3.Drittel</td>
		<td>{$match->stat.p3[1]}</td>
	</tr>
	<tr>
		<td>{$match->stat.p4[0]}</td>
		<td>Overtime</td>
		<td>{$match->stat.p4[1]}</td>
	</tr>
	<tr>
		<td>{$match->stat.p1[0]+$match->stat.p2[0]+$match->stat.p3[0]+$match->stat.p4[0]}</td>
		<td>Gesamt</td>
		<td>{$match->stat.p1[1]+$match->stat.p2[1]+$match->stat.p3[1]+$match->stat.p4[1]}</td>
	</tr>
</table>