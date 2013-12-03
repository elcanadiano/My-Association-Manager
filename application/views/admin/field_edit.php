<?php $this->load->view('admin/header.php') ?>

<?php if (isset($msg)): ?>
	<p><?= $msg ?></p>
<?php endif ?>

<?= form_open('admin/fields/' . $form_action); ?>
	<ul class='create-user'>
		<li>
			<label for="name">Field Name:</label>
			<input type="text" size="20" id="name" name="name" value="<?= $name ?>" placeholder="Name:" maxlength="128"/>
		</li>
		<li>
			<label for="address">Address:</label>
			<input type="text" size="20" id="address" name="address" value="<?= $address ?>" placeholder="Address:" maxlength="128"/>
		</li>
		<li>
			<label for="city">City:</label>
			<input type="text" size="20" id="city" name="city" value="<?= $city ?>" placeholder="City:" maxlength="64"/>
		</li>
		<li>
			<label for="region">Province/State:</label>
			<input type="text" size="20" id="region" name="region" value="<?= $region ?>" placeholder="Province/State:" maxlength="64"/>
		</li>
		<li>
			<label for="pitch_type">Pitch Type:</label>
			<input type="text" size="20" id="pitch_type" name="pitch_type" value="<?= $pitch_type ?>" placeholder="Pitch Type:" maxlength="16"/>
		</li>
	</ul>
	<?php if (isset($id)): ?>
		<input id="id" name="id" type="hidden" name="country" value="<?= $id ?>">
	<?php endif ?>
	<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
</form>

<?php $this->load->view('admin/footer.php') ?>
