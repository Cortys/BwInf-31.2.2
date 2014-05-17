<pre><?php
	
	include "../helper.php";
	
	$folder = 'classesCentroid';
	
	include "$folder/Polygon.php";
	
	error_reporting(E_ALL);
	
	$poly = new Polygon();
	
	$k = 1234;
	$r = 1000;
	
	for ($i = 0; $i < $k; $i++) {
		$v = 2*pi()*($i/$k);
		$poly->addPoint(Vector::fromValues($r*cos($v)+10, $r*sin($v)+10));
	}
	echo $poly->centroid()->toString();
?></pre>