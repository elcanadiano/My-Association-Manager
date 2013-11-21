		<p>Please enter the username and password of the new user.</p>
		<?php if ($msg): ?>
			<p><?= $msg ?></p>
		<?php endif ?>
		<?= form_open('admin/user/action_create_user'); ?>
			<ul class='create-user'>
				<li>
					<label for="username">Username:</label>
					<input type="text" size="20" id="username" name="username" value="<?= $username ?>"  maxlength="64"/>
				</li>
				<li>
					<label for="password">Password:</label>
					<input type="password" size="20" id="password" name="password"  maxlength="100"/>
				</li>
			</ul>
			<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
		</form>
