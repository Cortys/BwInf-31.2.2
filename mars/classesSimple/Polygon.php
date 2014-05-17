<?php
	class Polygon {
		private $points;
		private $length;
		
		public function __construct(array $points = null) {
			$this->points = $points?$points:array();
			$this->length = count($this->points);
		}
		
		public function length() {
			return $this->length;
		}
		
		public function addPoint(Vector $point) {
			$this->points[] = $point;
			$this->length++;
		}
		
		public function addPointAt(Vector $point, $pos) {
			array_splice($this->points, $pos, 0, $point);
			$this->length++;
		}
		
		public function removePointAt($pos) {
			array_splice($this->points, $pos, 1);
			$this->length--;
		}
		
		public function removePoints() {
			$this->points = array();
			$this->length = 0;
		}
		
		public function getPoints() {
			return $this->points;
		}
		
		private function checkBoundingPoint(&$box, &$boxLength, $vector, $direction) {
			$numDir = $direction=='left'||$direction=='top'?1:-1;
			if($boxLength[$direction] == 0 || ($diff = $box[$direction][0]->x()-$vector->x()) == 0 || sign($diff) == $numDir) {
				if($boxLength[$direction] != 0 && $box[$direction][0]->x() != $vector->x()) {
					$box[$direction] = array();
					$boxLength[$direction] = 0;
				}
				$box[$direction][] = $vector;
				$boxLength[$direction]++;
			}
		}
		
		public function boundingCircle($calcRadius = false, $queue) {
			$points = $this->points;
			if($this->length == 0)
				return array('center'=>Vector::fromValues(0,0), 'radius'=>0);
			if($this->length == 1)
				return array('center'=>$points[0], 'radius'=>0);
			
			// Bounding Box ermitteln:
			$box = array('left'=>array(), 'right'=>array(), 'top'=>array(), 'bottom'=>array());
			$boxLength = array('left'=>0, 'right'=>0, 'top'=>0, 'bottom'=>0);
			foreach ($points as $key => $value)
				foreach($box as $k => $v)
					$this->checkBoundingPoint($box, $boxLength, $value, $k);
			
			// Entfernteste Punkte ermitteln:
			//$points = array_merge($box['left'], $box['right'], $box['top'], $box['bottom']);
			//array_unique($points, SORT_REGULAR);
			$result = array('distance'=>0);
			foreach ($points as $key1 => $p1)
				foreach ($points as $key2 => $p2) {
					if($key1 == $key2)
						continue;
					$l = Vector::fromVectorDiff($p1, $p2)->lengthSquare();
					if(!isset($result['p1']) || $l > $result['distance'])
						$result = array('p1'=>$p1, 'p2'=>$p2, 'distance'=>$l);
				}
			
			// Mittelpunkt bilden:
			$center = Vector::fromVectorSum($result['p1'], $result['p2']);
			$center->divide(2);
			
			$radiusSquare = $result['distance']/4;
			
			$farthest = array('p'=>null, 'distance'=>0);
			foreach ($points as $key => $value) {
				if($value == $result['p1'] || $value == $result['p2'])
					continue;
				$l = (Vector::fromVectorDiff($center, $value)->lengthSquare()) - $radiusSquare;
				if($l > $farthest['distance'])
					$farthest = array('p'=>$value, 'distance'=>$l);
			}
			
			if($farthest['p']) {
				$queue->stop();
				$a = $result['p1'];
				$b = $result['p2'];
				$c = $farthest['p'];
				$aL = $a->lengthSquare();
				$bL = $b->lengthSquare();
				$cL = $c->lengthSquare();
				$cbDiff = $c->x()-$b->x();
				$acDiff = $a->x()-$c->x();
				$baDiff = $b->x()-$a->x();
				$divisor = 2*($a->y()*$cbDiff+$b->y()*$acDiff+$c->y()*$baDiff);
				$x = ($aL*($b->y()-$c->y())+$bL*($c->y()-$a->y())+$cL*($a->y()-$b->y()))/$divisor;
				$y = ($aL*$cbDiff+$bL*$acDiff+$cL*$baDiff)/$divisor;
				$center = Vector::fromValues($x, $y);
				
				if($calcRadius) {
					$sA = Vector::fromVectorDiff($b, $c)->length();
					$sB = Vector::fromVectorDiff($a, $c)->length();
					$sC = Vector::fromVectorDiff($a, $b)->length();
					$s = 0.5*($sA+$sB+$sC);
					$area = sqrt($s*($s-$sA)*($s-$sB)*($s-$sC));
					$radius = ($sA*$sB*$sC)/(4*$area);
				}
				else
					$radius = 0;
			}
			else if($calcRadius)
				$radius = sqrt($radiusSquare);
			else
				$radius = 0;
			
			return array('center'=>$center, 'radius'=>$radius);
			
		}
		
		public function toArray() {
			$result = array();
			foreach ($this->points as $key => $value) {
				$result[] = $value->toArray();
			}
			return $result;
		}
	}
?>