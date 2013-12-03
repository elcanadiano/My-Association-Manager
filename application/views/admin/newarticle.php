<?php $this->load->view('admin/header.php') ?>

<p>Please enter the username and password of the new user.</p>
<?= validation_errors(); ?>
<?= form_open('admin/news/action_add_article'); ?>
	<ul class='new-article'>
		<li>
			<label for="article-title">Title:</label>
			<input type="text" size="20" id="article-title" name="title" placeholder="Enter your title:" value="<?= $news_title ?>" />
		</li>
		<li>
			<label for="message">Message:</label>
			<textarea id="message" name="message"><?= $news_message ?></textarea>
		</li>
	</ul>
	<a href="javascript:void(0)" class="btn btn-primary btn-submit"><?= $submit_message ?></a>
</form>

<?php $this->load->view('admin/footer.php') ?>