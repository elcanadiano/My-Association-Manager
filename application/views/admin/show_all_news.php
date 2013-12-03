<?php $this->load->view('admin/header.php') ?>

<?php if(!isset($query_result) || !$query_result): ?>
	<p>There are no articles. Why not add an article?</p>
<?php else: ?>
	<table>
		<tr>
			<th>Article ID</th>
			<th>Author</th>
			<th>Title</th>
			<th>Date</th>
			<th></th>
		</tr>
		<?php foreach ($query_result as $row): ?>
			<tr>
				<td><?= $row->id ?></td>
				<td><?= $row->username ?></td>
				<td><?= $row->title ?></td>
				<td><?= $row->date ?></td>
				<td></td>
			</tr>
		<?php endforeach ?>
	</table>
<?php endif ?>

<?php $this->load->view('admin/footer.php') ?>