<?php $this->load->view('admin/header.php') ?>

<?php if(!$fields): ?>
	<p>You currently do not have any fields in the system. Why not create one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>ID</th>
			<th>Address</th>
			<th>City</th>
			<th>Province/State</th>
			<th>Pitch Type</th>
			<th>Actions</th>
		</tr>
		<?php foreach ($fields as $row): ?>
			<tr>
				<td><?= $row->id ?></td>
				<td><?= $row->name ?></td>
				<td><?= $row->address ?></td>
				<td><?= $row->city ?></td>
				<td><?= $row->region ?></td>
				<td><?= $row->pitch_type ?></td>
				<td>
					<a href="/admin/fields/edit/<?= $row->id ?>">Edit</a>
					<!--<a href="/admin/leagues/delete/<?= $row->id ?>">Delete</a>-->
				</td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>

<?php $this->load->view('admin/footer.php') ?>