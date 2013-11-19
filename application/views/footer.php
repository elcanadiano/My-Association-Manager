	<!--[if lt IE 9]>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<!--<![endif]-->
	<?php foreach ($js as $file): ?>
		<script src="<?= $file ?>"></script>
	<?php endforeach ?>
	</body>
</html>
