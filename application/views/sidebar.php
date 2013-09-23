			</section>
			<?php if (isset($sidebar_contents)): ?>
			<section id="side-content">
			<?php foreach ($sidebar_contents as $content): ?>
			<aside class="sample-sidebar">
				<h2 class="<?= $content['color'] ?>">
					<?= $content['title'] ?>
				</h2>
				<section><?= $content['body'] ?></section>
			</aside>
			<?php endforeach ?>
			</section>
			<?php endif ?>
