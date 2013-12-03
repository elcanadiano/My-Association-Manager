<?php $this->load->view('admin/header.php') ?>

<p>Please select the team name and the roster.</p>
<?= form_open('admin/teams/' . $form_action); ?>
	<ul class='create-user'>
		<li>
			<label for="tid">Team:</label>
			<select name="tid">
				<option value="">Select Team</option>
				<?php foreach ($teams as $team): ?>
					<option value="<?= $team->id ?>"><?= $team->id ?> - <?= $team->name ?></option>
				<?php endforeach ?>
			</select>
		</li>
		<li>
			<label for="sid">Season:</label>
			<select name="sid">
				<option value="">Select Season</option>
				<?php foreach ($seasons as $season): ?>
					<option value="<?= $season->id ?>"><?= $season->id ?> - <?= $season->name ?></option>
				<?php endforeach ?>
			</select>
		</li>
	</ul>
	<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
</form>

<div id="show-roster"></div>

<?php $this->load->view('admin/footer.php') ?>