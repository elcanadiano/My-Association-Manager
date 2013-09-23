<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
		<title><?= $title ?></title>
		<link rel="stylesheet" type="text/css" href="styles/reset.css" />
		<?php foreach ($css as $file): ?>
			<link rel="stylesheet" type="text/css" href="<?= $file ?>" />
		<?php endforeach ?>
		<link rel="canonical" href="http://localhost" />
	<!--[if lt IE 9]>
		<link rel="stylesheet" type="text/css" href="styles/oldie.css" />
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	</head>
	<body>
		<header id="main-header">
			<div id="mobile-dropdown">☰</div>
			<nav id="main-nav">
				<ul class="navigation">
				<?php foreach($header_navigation as $link): ?>
					<li><a href="<?= $link['href'] ?>"><?= $link['content'] ?></a></li>
				<?php endforeach ?>
				</ul>
			</nav>
			<section id="login">Welcome, <a href="#">el canadiano</a>!<span class="daily-message-desktop"> Have you made a trade today?</span></section>
		</header>
		<div id="container">
			<section id="main-content">
