<?php if(!$teams): ?>
	<p>There are currently no teams in this league for the given season. Why not add one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Team Name</th>
			<th>Season Name</th>
			<th>League Name</th>
			<th>P</th>
			<th>Pts</th>
			<th>W</th>
			<th>D</th>
			<th>L</th>
			<th>F</th>
			<th>A</th>
			<th>GD</th>
		</tr>
		<?php foreach ($teams as $team): ?>
			<tr>
				<td><?= $team->team_name ?></td>
				<td><?= $team->season_name ?></td>
				<td><?= $team->league_name ?></td>
				<td><?= $team->pld ?></td>
				<td><?= $team->pts ?></td>
				<td><?= $team->wins ?></td>
				<td><?= $team->ties ?></td>
				<td><?= $team->losses ?></td>
				<td><?= $team->goals ?></td>
				<td><?= $team->allowed ?></td>
				<td><?= $team->goals - $team->allowed ?></td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
