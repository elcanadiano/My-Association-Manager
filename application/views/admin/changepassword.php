		<p>Enter the password and confirm.</p>
		<?= validation_errors(); ?>
		<?php if ($message): ?>
		<p><?= $message ?></p>
		<?php endif ?>
		<?= form_open('admin/user/action_change_password'); ?>
			<input type="hidden" name="username" value='<?= $username ?>' />
			<ul class='create-user'>
				<li>
					<label for="password">Password:</label>
					<input type="password" size="20" id="password" name="password"/>
				</li>
				<li>
					<label for="confirm">Password (again!):</label>
					<input type="password" size="20" id="confirm" name="confirm"/>
				</li>
			</ul>
			<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
		</form>
