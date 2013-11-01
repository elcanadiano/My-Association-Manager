		<?php if ($msg): ?>
			<p><?= $msg ?></p>
		<?php endif ?>
		<?= form_open('admin/seasons/' . $form_action); ?>
			<ul class='create-user'>
				<li>
					<label for="name">Season Name:</label>
					<input type="text" size="20" id="name" name="name" value="<?= $name ?>" placeholder="Name:" maxlength="64"/>
				</li>
				<li>
					<label for="start_date">Start Date:</label>
					<input type="text" size="20" id="start_date" name="start_date" value="<?= $start_date ?>" placeholder="Start Date:" maxlength="16"/>
				</li>
				<li>
					<label for="end_date">End Date:</label>
					<input type="text" size="20" id="end_date" name="end_date" value="<?= $end_date ?>" placeholder="End Date:" maxlength="16"/>
				</li>
			</ul>
			<?php if (isset($id)): ?>
				<input id="id" name="id" type="hidden" name="id" value="<?= $id ?>">
			<?php endif ?>
			<input type="submit" value="<?= $submit_message ?>" />
		</form>
