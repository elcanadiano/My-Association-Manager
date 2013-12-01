<?php if($msg): ?>
	<p><?= $msg ?></p>
<?php endif ?>

<?php if(!$teams): ?>
	<p>You currently do not have any teams set up. Why not create one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Name</th>
			<th>Home Field</th>
			<th>City</th>
			<th>Province/State</th>
			<th>Actions</th>
		</tr>
		<?php foreach ($teams as $row): ?>
			<tr>
				<td><?= $row->name ?></td>
				<td><?= $row->field_name ?></td>
				<td><?= $row->city ?></td>
				<td><?= $row->region ?></td>
				<td>
					<a href="/admin/teams/edit/<?= $row->id ?>">Edit</a>
					<a href="/admin/teams/roster_add/0/<?= $row->id ?>/0">Add to Roster</a>
					<a href="/admin/leagues/add_team/<?= $row->id ?>/0/0">Add to League</a>
					<!--<a href="/admin/leagues/delete/<?= $row->id ?>">Delete</a>-->
				</td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
