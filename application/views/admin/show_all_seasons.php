<?php if($msg): ?>
	<p><?= $msg ?></p>
<?php endif ?>

<?php if(!$seasons): ?>
	<p>You currently do not have any seasons set up. Why not create one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Name</th>
			<th>Age Category</th>
			<th>Start Date</th>
			<th>End Date</th>
			<th>Actions</th>
		</tr>
		<?php foreach ($seasons as $row): ?>
			<tr>
				<td><?= $row->name ?></td>
				<td><?= $row->age_cat ?></td>
				<td><?= $row->start_time ?></td>
				<td><?= $row->end_time ?></td>
				<td>
					<a href="/admin/leagues/edit/<?= $row->id ?>">Edit</a>
					<!--<a href="/admin/leagues/delete/<?= $row->id ?>">Delete</a>-->
				</td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>
