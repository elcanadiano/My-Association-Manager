	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>
<?php foreach ($js as $file): ?>
	<script src="<?= $file ?>"></script>
<?php endforeach ?>
</body>
</html>
