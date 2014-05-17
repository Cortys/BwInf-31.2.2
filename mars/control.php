<?php
	error_reporting(0);
	set_time_limit(0);
	include "classes.php";
	
	// POST-Header auslesen:
	$predefinedRobots = isset($_POST['robots'])?$_POST['robots']:array();
	if(!isset($predefinedRobots['fallen']))
		$predefinedRobots['fallen'] = array();
	if(!isset($predefinedRobots['falling']))
		$predefinedRobots['falling'] = array();
	$robotNum = is_integer($_POST['robotNum']*1)?min($_POST['robotNum']*1, 200):0;
	$duration = is_integer($_POST['fallDuration']*1)?$_POST['fallDuration']*1:5;
	$distribution = is_numeric($_POST['distribution']*1)?$_POST['distribution']*1:0.5;
	$robotView = $_POST['robotView']*1;
	$fieldSize = $_POST['fieldSize'];
	
	// Objekte anlegen:
	$mars = new Mars();
	$spaceship = new Spaceship();
	$queue = new Queue();
	
	// Beziehungen setzen:
	$spaceship->setMars($mars);
	$mars->setQueue($queue);
	$queue->setMars($mars);
	$queue->setSpaceship($spaceship);
	
	// Simulationseinstellungen:
	$spaceship->setRobotNum($robotNum-count($predefinedRobots['fallen'])-count($predefinedRobots['falling']));
	$spaceship->setRobotViewRange($robotView);
	$spaceship->setFallingRobots($predefinedRobots['falling']);
	$spaceship->setDistribution($distribution);
	$spaceship->setRobotFallDuration($duration);
	$mars->setFinalRobotNum($robotNum);
	$mars->setFallenRobots($predefinedRobots['fallen']);
	$mars->setSize($fieldSize[0], $fieldSize[1]);
	
	// Queue-Takt fuer 150 Takte starten:
	$queue->start(150);
	
	echo json_encode($queue->getPositions());
?>