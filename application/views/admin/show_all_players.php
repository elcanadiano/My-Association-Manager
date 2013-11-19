<?php if($msg): ?>
	<p><?= $msg ?></p>
<?php endif ?>

<?php if(!$players): ?>
	<p>You currently do not have any players. Why not register one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Real Name</th>
			<th>Name</th>
			<th>Position 1</th>
			<th>Position 2</th>
			<th>Position 3</th>
		</tr>
		<?php foreach ($players as $row): ?>
			<tr>
				<td><?= $row->real_name ?></td>
				<td><?= $row->preferred_name ?></td>
				<td><?= $row->pos1 ?></td>
				<td><?= $row->pos2 ?></td>
				<td><?= $row->pos3 ?></td>
				<td>
					<a href="/admin/players/edit/<?= $row->id ?>">Edit</a>
					<!--<a href="/admin/leagues/delete/<?= $row->id ?>">Delete</a>-->
				</td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
