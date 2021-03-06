<?php $this->load->view('admin/header.php') ?>

<?php if (isset($msg)): ?>
	<p><?= $msg ?></p>
<?php endif ?>
<?= form_open('admin/leagues/' . $form_action); ?>
	<ul class='create-user'>
		<li>
			<label for="name">League Name:</label>
			<input type="text" size="20" id="name" name="name" value="<?= $name ?>" placeholder="Name:" maxlength="64"/>
		</li>
		<li>
			<label for="age_cat">Age Category:</label>
			<input type="text" size="20" id="age_cat" name="age_cat" value="<?= $age_cat ?>" placeholder="Age Category:" maxlength="16"/>
		</li>
	</ul>
	<?php if (isset($id)): ?>
		<input id="id" name="id" type="hidden" name="country" value="<?= $id ?>">
	<?php endif ?>
	<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
</form>

<?php $this->load->view('admin/footer.php') ?>