<?php if(!$players): ?>
	<p>There are currently no players on this roster for the given season. Why not add one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Squad Number</th>
			<th>Team</th>
			<th>Name</th>
			<th>Season</th>
			<th>Position 1</th>
			<th>Position 2</th>
			<th>Position 3</th>
		</tr>
		<?php foreach ($players as $player): ?>
			<tr>
				<td><?= $player->squad_number ?></td>
				<td><?= $player->team_name ?></td>
				<td><?= $player->player_name ?></td>
				<td><?= $player->season_name ?></td>
				<td><?= $player->pos1 ?></td>
				<td><?= $player->pos2 ?></td>
				<td><?= $player->pos3 ?></td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
