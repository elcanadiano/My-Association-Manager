	<!--[if lt IE 9]>
		<script src="/js/jquery-1.10.2.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
		<script src="/js/jquery-2.0.3.min.js"></script>
	<!--<![endif]-->
	<?php foreach ($js as $file): ?>
		<script src="<?= $file ?>"></script>
	<?php endforeach ?>
	</body>
</html>
