		<p>Please enter the following information for the new league.</p>
		<?php if ($msg): ?>
			<p><?= $msg ?></p>
		<?php endif ?>
		<?= form_open('admin/leagues/action_create_league'); ?>
			<ul class='create-user'>
				<li>
					<label for="name">League Name:</label>
					<input type="text" size="20" id="name" name="name" value="<?= $name ?>" placeholder="Name:" maxlength="64"/>
				</li>
				<li>
					<label for="age_cat">Age Category:</label>
					<input type="text" size="20" id="age_cat" name="age_cat" placeholder="Age Category:" maxlength="16"/>
				</li>
			</ul>
			<input type="submit" value="Add League" />
		</form>
