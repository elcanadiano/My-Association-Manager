	<h1>
		<?= $h1 ?>
		<?php if (isset($h1_newline)): ?>
			<span class="logo-new-line"><?= $h1_newline ?></span>
		<?php endif ?>
	</h1>


	<ul class="articles">
		<?php foreach ($articles as $article): ?>
			<li>
				<article>
					<h2><?= $article->title ?></h2>

					<ul class="post-info">
						<li class="author">By <a href="#"><?= $article->username ?></a></li>
						<li class="date"><?= date('F j, Y', strtotime($article->date)) ?></li>
					</ul>

					<?= $article->parsed ?>

					<footer>&raquo; <a href="#">Comments (0)</a></footer>
				</article>
			</li>
		<?php endforeach ?>
	</ul>
