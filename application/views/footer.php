	<!--[if lt IE 9]>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<!--<![endif]-->
	<?php foreach ($js as $file): ?>
		<script src="<?= $file ?>"></script>
	<?php endforeach ?>
	</body>
</html>
