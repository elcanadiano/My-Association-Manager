<?php if($msg): ?>
	<p><?= $msg ?></p>
<?php endif ?>

<?php if(!isset($leagues) || !$leagues): ?>
	<p>You currently do not have any leagues. Why not create one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Name</th>
			<th>Age Category</th>
			<th>Number of Teams</th>
			<th>Max. Roster Size</th>
			<th>Active?</th>
		</tr>
		<? foreach ($query_result as $row): ?>
			<tr>
				<td><?= $row->name ?></td>
				<td><?= $row->age_cat ?></td>
				<td><?= $row->num_teams ?></td>
				<td><?= $row->max_roster_size ?></td>
				<td><?= $row->active ? 'Yes': 'No' ?></td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
