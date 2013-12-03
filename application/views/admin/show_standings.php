<?php $this->load->view('admin/header.php') ?>

<p>Please select the league and the season.</p>
<?= form_open('admin/leagues/' . $form_action); ?>
	<ul class='create-user'>
		<li>
			<label for="lid">League:</label>
			<select name="lid">
				<option value="">Select League</option>
				<?php foreach ($leagues as $league): ?>
					<option value="<?= $league->id ?>"><?= $league->id ?> - <?= $league->name ?> - <?= $league->age_cat ?></option>
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