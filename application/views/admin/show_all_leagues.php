<?php if($msg): ?>
	<p><?= $msg ?></p>
<?php endif ?>

<?php if(!$leagues): ?>
	<p>You currently do not have any leagues. Why not create one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Name</th>
			<th>Age Category</th>
			<!--<th>Number of Teams</th>-->
		</tr>
		<? foreach ($leagues as $row): ?>
			<tr>
				<td><?= $row->name ?></td>
				<td><?= $row->age_cat ?></td>
				<!--<td><?= $row->num_teams ?></td>-->
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
