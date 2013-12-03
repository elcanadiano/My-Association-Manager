<?php $this->load->view('admin/header.php') ?>

<?php if(!$leagues): ?>
	<p>You currently do not have any leagues. Why not create one?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Name</th>
			<th>Age Category</th>
			<th>Actions</th>
		</tr>
		<?php foreach ($leagues as $row): ?>
			<tr>
				<td><?= $row->name ?></td>
				<td><?= $row->age_cat ?></td>
				<td>
					<a href="/admin/leagues/edit/<?= $row->id ?>">Edit</a>
					<a href="/admin/leagues/add_team/0/<?= $row->id ?>/0">Add Team</a>
					<!--<a href="/admin/leagues/delete/<?= $row->id ?>">Delete</a>-->
				</td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>

<?php $this->load->view('admin/footer.php') ?>