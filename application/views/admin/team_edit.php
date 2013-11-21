		<?php if ($msg): ?>
			<p><?= $msg ?></p>
		<?php endif ?>
		<?= form_open('admin/teams/' . $form_action); ?>
			<ul class='create-user'>
				<li>
					<label for="name">Team Name:</label>
					<input type="text" size="20" id="name" name="name" value="<?= $name ?>" placeholder="Name:" maxlength="64"/>
				</li>
				<li>
					<label for="homeid">Home Field:</label>
					<select name="homeid">
						<?php foreach ($fields as $field): ?>
							<option value="<?= $field->id ?>"<?= ($homeid === $field->id) ? ' selected="selected"' : '' ?>><?= $field->id ?> - <?= $field->name ?></option>
						<?php endforeach ?>
					</select>
				</li>
				<li>
					<label for="city">City:</label>
					<input type="text" size="20" id="city" name="city" value="<?= $city ?>" placeholder="City:" maxlength="64"/>
				</li>
				<li>
					<label for="region">Province/State:</label>
					<input type="text" size="20" id="region" name="region" value="<?= $region ?>" placeholder="Province/State:" maxlength="64"/>
				</li>
			</ul>
			<?php if (isset($id)): ?>
				<input id="id" name="id" type="hidden" name="id" value="<?= $id ?>">
			<?php endif ?>
			<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
		</form>
