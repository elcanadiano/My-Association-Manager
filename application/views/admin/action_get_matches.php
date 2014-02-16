<?php foreach ($matches as $match): ?>
	<div class="scoreboard">
		<div class="teams">
			<div class="home_team">
				<h3>Home:</h3>
				<p><?= $match->home_team ?></p>
			</div>
			<div class="h_g">
				<p><?= $match->h_g ?></p>
			</div>
			<div class="vs">v.</div>
			<div class="a_g">
				<p><?= $match->a_g ?></p>
			</div>
			<div class="away_team">
				<h3>Away:</h3>
				<p><?= $match->away_team ?></p>
			</div>
		</div>

		<div class="datetime">
			<?= $match->date ?> <?= $match->time ?>
		</div>
	</div>
<?php endforeach ?>