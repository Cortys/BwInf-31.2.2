<?php
	include "classes.php";
	
	$p1 = Vector::fromValues(158, 245);
	$p2 = Vector::fromValues(345, 192);
	$p3 = Vector::fromValues(477, 300);
	$p4 = Vector::fromValues(372, 429);
	$p5 = Vector::fromValues(194, 443);
	
	$v1 = CircleVector::fromVector($p1);
	$v2 = CircleVector::fromVector($p2);
	$v3 = CircleVector::fromVector($p3);
	$v4 = CircleVector::fromVector($p4);
	$v5 = CircleVector::fromVector($p5);
	$v1->before($v5);
	$v1->after($v2);
	$v2->before($v1);
	$v2->after($v3);
	$v3->before($v2);
	$v3->after($v4);
	$v4->before($v3);
	$v4->after($v5);
	$v5->before($v4);
	$v5->after($v1);
	
	$c1 = new Circle($v1);
	$c2 = new Circle($v2);
	$c3 = new Circle($v3);
	$c4 = new Circle($v4);
	$c5 = new Circle($v5);
	
	echo $c1->toString()."<br>";
	echo $c2->toString()."<br>";
	echo $c3->toString()."<br>";
	echo $c4->toString()."<br>";
	echo $c5->toString()."<br>";
	
	$p1 = Vector::fromValues(158, 245);
	$p2 = Vector::fromValues(345, 192);
	$p3 = Vector::fromValues(477, 300);
	$p5 = Vector::fromValues(194, 443);
	
	$v1 = CircleVector::fromVector($p1);
	$v2 = CircleVector::fromVector($p2);
	$v3 = CircleVector::fromVector($p3);
	$v5 = CircleVector::fromVector($p5);
	$v1->before($v5);
	$v1->after($v2);
	$v2->before($v1);
	$v2->after($v3);
	$v3->before($v2);
	$v3->after($v5);
	$v5->before($v3);
	$v5->after($v1);
	
	$c1 = new Circle($v1);
	$c2 = new Circle($v2);
	$c3 = new Circle($v3);
	$c5 = new Circle($v5);
	echo "<hr>";
	echo $c1->toString()."<br>";
	echo $c2->toString()."<br>";
	echo $c3->toString()."<br>";
	echo $c5->toString()."<br>";
	
	$p1 = Vector::fromValues(220.4, 409.7);
	$p3 = Vector::fromValues(356.1, 115.8);
	$p5 = Vector::fromValues(412, 426.1);
	
	$v1 = CircleVector::fromVector($p1);
	$v3 = CircleVector::fromVector($p3);
	$v5 = CircleVector::fromVector($p5);
	$v1->before($v5);
	$v1->after($v3);
	$v3->before($v1);
	$v3->after($v5);
	$v5->before($v3);
	$v5->after($v1);
	
	$c1 = new Circle($v1);
	$c3 = new Circle($v3);
	$c5 = new Circle($v5);
	echo "<hr>";
	echo $c1->toString()."<br>";
	echo $c3->toString()."<br>";
	echo $c5->toString()."<br>";
?>