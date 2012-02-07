<table class="statistics goals">
	<tr>
		<th colspan="3">Torstatistik</th>
	</tr>
	<tr>
		<td width="50">Heim</td>
		<td width="50"> </td>
		<td width="50">Gast</td>
	</tr>
	<tr>
		<td>{$match->stat.g1[0]}</td>
		<td>1.Drittel</td>
		<td>{$match->stat.g1[1]}</td>
	</tr>
	<tr>
		<td>{$match->stat.g2[0]}</td>
		<td>2.Drittel</td>
		<td>{$match->stat.g2[1]}</td>
	</tr>
	<tr>
		<td>{$match->stat.g3[0]}</td>
		<td>3.Drittel</td>
		<td>{$match->stat.g3[1]}</td>
	</tr>
	<tr>
		<td>{$match->stat.g4[0]}</td>
		<td>Overtime</td>
		<td>{$match->stat.g4[1]}</td>
	</tr>
	<tr>
		<td>{$match->stat.g1[0]+$match->stat.g2[0]+$match->stat.g3[0]+$match->stat.g4[0]}</td>
		<td>Gesamt</td>
		<td>{$match->stat.g1[1]+$match->stat.g2[1]+$match->stat.g3[1]+$match->stat.g4[1]}</td>
	</tr>
</table>