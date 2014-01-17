<?php if(!$teams): ?>
	<p>There are currently no teams in this league for the given season. Why not add one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Team Name</th>
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
				<td><?= $team->pld ? $team->pld : 0 ?></td>
				<td><?= $team->pts ? $team->pts : 0 ?></td>
				<td><?= $team->wins ? $team->wins : 0 ?></td>
				<td><?= $team->ties ? $team->ties : 0 ?></td>
				<td><?= $team->losses ? $team->losses : 0 ?></td>
				<td><?= $team->goals ? $team->goals : 0 ?></td>
				<td><?= $team->allowed ? $team->allowed : 0 ?></td>
				<td><?= $team->goals - $team->allowed ? $team->goals - $team->allowed : 0 ?></td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
