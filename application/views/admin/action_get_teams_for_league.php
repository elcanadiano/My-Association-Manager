<option value="">Select Team</option>
<?php foreach ($teams as $team): ?>
	<option value="<?= $team->id ?>"><?= $team->id ?> - <?= $team->name ?></option>
<?php endforeach ?>