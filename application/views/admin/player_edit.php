		<?php if ($msg): ?>
			<p><?= $msg ?></p>
		<?php endif ?>
		<?= form_open('admin/players/' . $form_action); ?>
			<ul class='create-user'>
				<li>
					<label for="real_name">Real Name:</label>
					<input type="text" size="20" id="real_name" name="real_name" value="<?= $real_name ?>" placeholder="Name:" maxlength="64"/>
				</li>
				<li>
					<label for="preferred_name">Nickname:</label>
					<input type="text" size="20" id="preferred_name" name="preferred_name" value="<?= $preferred_name ?>" placeholder="Nickname:" maxlength="64"/>
				</li>
				<li>
					<label for="pos1">Position 1:</label>
					<input type="text" size="20" id="pos1" name="pos1" value="<?= $pos1 ?>" placeholder="Position 1:" maxlength="64"/>
				</li>
				<li>
					<label for="pos2">Position 2:</label>
					<input type="text" size="20" id="pos2" name="pos2" value="<?= $pos2 ?>" placeholder="Position 2:" maxlength="64"/>
				</li>
				<li>
					<label for="pos3">Position 3:</label>
					<input type="text" size="20" id="pos3" name="pos3" value="<?= $pos3 ?>" placeholder="Position 3:" maxlength="64"/>
				</li>
				<li>
					<label for="email">Email Address:</label>
					<input type="email" size="20" id="email" name="email" value="<?= $email ?>" placeholder="Email Address:" maxlength="64"/>
				</li>
				<li>
					<label for="password">Password:</label>
					<input type="password" size="20" id="password" name="password" value="" placeholder="Password:" maxlength="64"/>
				</li>
				<li>
					<label for="confirm">Password (confirm):</label>
					<input type="password" size="20" id="confirm" name="confirm" value="" placeholder="confirm:" maxlength="64"/>
				</li>
			</ul>
			<?php if (isset($id)): ?>
				<input id="id" name="id" type="hidden" name="id" value="<?= $id ?>">
			<?php endif ?>
			<input type="submit" value="<?= $submit_message ?>" />
		</form>
