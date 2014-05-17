<?php
	include "classes.php";
	
	$p1 = Vector::fromValues(104, 275);
	$p2 = Vector::fromValues(267, 94);
	$p3 = Vector::fromValues(281, 327);
	
	$v1 = CircleVector::fromVector($p1);
	$v2 = CircleVector::fromVector($p2);
	$v3 = CircleVector::fromVector($p3);
	$v1->before($v3);
	$v1->after($v2);
	
	$c1 = new Circle($v1);
	echo $c1->toString();
?>