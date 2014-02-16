<?php $this->load->view('admin/header.php') ?>

<?php if (isset($msg)): ?>
	<p><?= $msg ?></p>
<?php endif ?>

<?= form_open('admin/matches/' . $form_action); ?>
	<ul class='create-user'>
		<li>
			<label for="lid">League:</label>
			<select name="lid" id="lid">
				<option value="">Select League</option>
				<?php foreach ($leagues as $league): ?>
					<option value="<?= $league->id ?>"<?= ($lid === $league->id) ? ' selected="selected"' : ''?>><?= $league->id ?> - <?= $league->name ?> - <?= $league->age_cat ?></option>
				<?php endforeach ?>
			</select>
		</li>
		<li>
			<label for="sid">Season:</label>
			<select name="sid" id="sid">
				<option value="">Select Season</option>
				<?php foreach ($seasons as $season): ?>
					<option value="<?= $season->id ?>"<?= ($sid === $season->id) ? ' selected="selected"' : ''?>><?= $season->id ?> - <?= $season->name ?></option>
				<?php endforeach ?>
			</select>
		</li>
		<li>
			<label for="fid">Field:</label>
			<select name="fid" id="fid">
				<option value="">Select Fields</option>
				<?php foreach ($fields as $field): ?>
					<option value="<?= $field->id ?>"><?= $field->id ?> - <?= $field->name ?></option>
				<?php endforeach ?>
			</select>
		</li>
	</ul>

	<div class="scoreboard">
		<div class="teams">
			<div class="home_team">
				<label for="htid">Home:</label>
				<select name="htid" id="htid" disabled>
					<option value="">Select Team</option>
				</select>
			</div>
			<div class="h_g">
				<input type="number" size="1" id="h_g" name="h_g" value="<?= $h_g ?>" maxlength="16" style="width:50px" />
			</div>
			<div class="vs">v.</div>
			<div class="a_g">
				<input type="number" size="1" id="a_g" name="a_g" value="<?= $a_g ?>" maxlength="16" style="width:50px" />
			</div>
			<div class="away_team">
				<label for="atid">Away:</label>
				<select name="atid" id="atid" disabled>
					<option value="">Select Team</option>
				</select>
			</div>
		</div>

		<div class="datetime">
			<label for="date">Date:</label>
			<input type="text" size="20" id="date" name="date" value="<?= $date ?>" placeholder="Date:" maxlength="16"/>

			<label for="time">Time:</label>
			<input type="text" size="20" id="time" name="time" value="<?= $time ?>" placeholder="Time:" maxlength="16"/>
		</div>

		<div class="check">
			<input type="checkbox" name="has_been_played" id="has_been_played" value="true">
			<label for="has_been_played">This game has been played and will count for standings.</label>
		</div>

	</div>

	<?php if (isset($id)): ?>
		<input id="id" name="id" type="hidden" name="id" value="<?= $id ?>">
	<?php endif ?>

	<a href="javascript:void(0)" class="btn btn-primary btn-submit disabled"><?= $submit_message ?></a>
</form>

<?php $this->load->view('admin/footer.php') ?>
