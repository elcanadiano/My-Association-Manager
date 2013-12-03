<?php $this->load->view('admin/header.php') ?>

<?= form_open('admin/teams/' . $form_action); ?>
	<ul class='create-user'>
		<li>
			<label for="pid">Player:</label>
			<select name="pid">
				<option value="">Select Player</option>
				<?php foreach ($players as $player): ?>
					<option value="<?= $player->id ?>"<?= ($pid === $player->id) ? ' selected="selected"' : '' ?>><?= $player->id ?> - <?= $player->name ?></option>
				<?php endforeach ?>
			</select>
		</li>
		<li>
			<label for="tid">Team:</label>
			<select name="tid">
				<option value="">Select Team</option>
				<?php foreach ($teams as $team): ?>
					<option value="<?= $team->id ?>"<?= ($tid === $team->id) ? ' selected="selected"' : '' ?>><?= $team->id ?> - <?= $team->name ?></option>
				<?php endforeach ?>
			</select>
		</li>
		<li>
			<label for="sid">Season:</label>
			<select name="sid">
				<option value="">Select Season</option>
				<?php foreach ($seasons as $season): ?>
					<option value="<?= $season->id ?>"<?= ($sid === $season->id) ? ' selected="selected"' : '' ?>><?= $season->id ?> - <?= $season->name ?></option>
				<?php endforeach ?>
			</select>
		</li>
		<li>
			<label for="squad_number">Squad Number:</label>
			<input type="number" size="20" id="squad_number" name="squad_number" value="" placeholder="Squad No:" maxlength="2"/>
		</li>
	</ul>
	<?php if (isset($id)): ?>
		<input id="id" name="id" type="hidden" name="id" value="<?= $id ?>">
	<?php endif ?>
	<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
</form>

<?php $this->load->view('admin/footer.php') ?>