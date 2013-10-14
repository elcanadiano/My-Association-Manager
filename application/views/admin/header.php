<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?= $title ?></title>
<?php foreach ($css as $file): ?>
	<link rel="stylesheet" type="text/css" href="<?= $file ?>">
<?php endforeach ?>
</head>
<body>

<div id="container">
	<div id="title">
		<h1><?= $title ?></h1>
		<div id="right"><a href="/admin/home/logout">Logout</a></div>
	</div>
	<nav id="main-nav">
		<ul class="navigation">
			<li><a href="/admin/home">Home</a></li>
			<li><a href="/admin/user">Users</a></li>
			<li><a href="/admin/news">News Center</a></li>
			<li><a href="/admin/leagues">Leagues</a></li>
		</ul>
	</nav>
<?php if (isset($sidenav)): ?>
	<div id="side-nav">
		<header>
			<h2><?= $sidenav['title'] ?></h2>
		</header>
		<ul>
		<?php foreach ($sidenav['links'] as $link): ?>
			<li><a href="<?= $link['url'] ?>"><?= $link['desc'] ?></a></li>
		<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>
	<div id="body">
