<pre><?php
	include "classes.php";
	error_reporting(E_ALL);
	/*$v1 = Vector::fromValues(310.47211, 440.77418);
	$v2 = Vector::fromValues(262.07146, 357.8067);
	$v3 = Vector::fromValues(376.90554, 164.49915);
	$v4 = Vector::fromValues(508.42975, 192.95444);*/
	
	$v1 = Vector::fromValues(357.6, 366.8);
	$v2 = Vector::fromValues(475.9, 304.7);
	$v3 = Vector::fromValues(548.1, 257.1);
	$v4 = Vector::fromValues(518.3, 165.1);
	$v5 = Vector::fromValues(467.1, 121.9);
	
	$points = array($v1, $v2, $v3, $v4, $v5);
	$l = count($points);
	foreach ($points as $key => $value) {
		$points[$key] = CircleVector::fromVector($value);
	}
	
	$heap = new CircleHeap();
	
	foreach($points as $i => $curr) {
		$curr->before($points[($i-1)+(($i-1)<0?$l:0)]);
		$curr->after($points[($i+1)%$l]);
		$c = new Circle($curr);
		echo "Kreis:".($c->b->toString()).":<b>".rad2deg($c->angle)."</b>:".$c->toString()."<br>";
		$heap->insert($c);
	}
	$circle = null;
	$i = 0;
	echo "<hr>";
	while(true) {
		$largest = $heap->largest();
		echo $largest->toString()."<br>";
		$i++;
		if($i > $l || $largest->isFinal())
			break;
		$heap->delete($largest);
		$heap->delete($largest->before());
		$heap->delete($largest->after());
						
		$largest->a->after($largest->c);
		$largest->c->before($largest->a);
		$n1 = new Circle($largest->a);
		echo "<h3>Neu:<i>".rad2deg($n1->angle)."</i>:".$largest->a->toString().":".$n1->toString();
		$heap->insert($n1);
		$n2 = new Circle($largest->c);
		echo "Neu:<i>".rad2deg($n2->angle)."</i>".$largest->c->toString().":".$n2->toString()."</h3>";
		$heap->insert($n2);
	}
	echo "<hr>";
	echo $largest->toString();
	
	/*$tree->show();
	
	$circle = null;
	$i = 0;
	while(true) {
		$largest = $tree->largest();
		$circle = $largest->content();
		$i++;
		if($i > $l || $circle->isFinal())
			break;
		$tree->delete($largest);
		$tree->delete($circle->before()->node);
		$tree->delete($circle->after()->node);
		
		$circle->a->after($circle->c);
		$circle->c->before($circle->a);
		
		$tree->insert(new Node(new Circle($circle->a)));
		$tree->insert(new Node(new Circle($circle->c)));
		$tree->show();
	}
	
	echo $circle->a->toString();
	echo $circle->b->toString();
	echo $circle->c->toString();
	echo "<br><br>";
	echo $circle->center()->toString();
	echo "<hr>";
	$poly = new Polygon($points);
	$circle = $poly->boundingCircle(true);
	echo "<br>".$circle['center']->toString();
	echo "<br>".$circle['radius'];*/
	/*$node = $tree->largest();
	$l = $node->content();
	print_r($l->b->toString());
	echo "<br>";
	echo $l->isFinal()?"kleiner 90":"groesser 90";
	$tree->show();
	
	$tree->delete($node);
	$tree->delete($l->before()->node());
	$tree->delete($l->after()->node());
	
	$l->a->after($l->c);
	$l->c->before($l->a);
	
	$tree->show();
	
	$tree->insert(new Node(new Circle($l->a)));
	$tree->insert(new Node(new Circle($l->c)));
	
	$node = $tree->largest();
	$l = $node->content();
	print_r($l->a->toString());
	print_r($l->b->toString());
	print_r($l->c->toString());
	echo "<br>";
	echo $l->isFinal()?"kleiner 90":"groesser 90";
	
	$tree->show();*/
	
?></pre>