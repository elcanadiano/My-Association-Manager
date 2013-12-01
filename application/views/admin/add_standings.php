<?= form_open('admin/leagues/' . $form_action); ?>
	<ul class='create-user'>
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
			<label for="lid">League:</label>
			<select name="lid">
				<option value="">Select League</option>
				<?php foreach ($leagues as $league): ?>
					<option value="<?= $league->id ?>"<?= ($lid === $league->id) ? ' selected="selected"' : '' ?>>
						<?= $league->id ?> - <?= $league->name ?> - <?= $league->age_cat ?>
					</option>
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
	</ul>
	<?php if (isset($id)): ?>
		<input id="id" name="id" type="hidden" name="id" value="<?= $id ?>">
	<?php endif ?>
	<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
</form>
