	</div>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>
<!--[if lt IE 9]>
	<script src="/js/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
	<script src="/js/jquery-2.0.3.min.js"></script>
<!--<![endif]-->
<script src="/js/jquery-ui-1.10.3.custom.min.js"></script>
<?php foreach ($js as $file): ?>
	<script src="<?= $file ?>"></script>
<?php endforeach ?>
</body>
</html>
