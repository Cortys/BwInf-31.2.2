<!DOCTYPE html>

<!--
*************************************************
* Loesungen zum 31. Bundeswettbewerb Informatik *
* Teilnehmer: Clemens Damke                     *
*************************************************
-->

<?php

	set_time_limit(0);
	
	$seite = 'mars';
?>

<html>
<head>
	
	<meta charset="utf-8">
	<title>Bundeswettbewerb Informatik 2013</title>
	
	<link rel="stylesheet" href="embed/style.css" type="text/css">
	<link rel="stylesheet" href="embed/humanity/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="<?php echo $seite; ?>/embed/style.css" type="text/css">
	
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="embed/jquery.js" type="text/javascript"></script>
	<script src="embed/jquery-ui.js" type="text/javascript"></script>
	<script src="embed/canvas.js" type="text/javascript"></script>
	<script src="embed/cssAnimate.js" type="text/javascript"></script>
	<script src="embed/script.js" type="text/javascript"></script>
	<script src="<?php echo $seite; ?>/embed/script.js" type="text/javascript"></script>
</head>
<body>
	<div id="wrap">
		<div id="head">
			<?php
				/*if($seite != "home")
					echo '<img src="img/home.png" alt="Home" id="home">';*/
			?>
			<span>31. Bundeswettbewerb Informatik</span><br>
			&middot; Runde 2 &middot; Marsch auf Mars &middot; L&ouml;sung von Clemens Damke &middot;
		</div>
		<div id="scrollable">
			<div id="content">
				<?php
					include_once "$seite/index.php";
				?>
			</div>
		</div>
	</div>
</body>
</html>