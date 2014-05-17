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
		
		public function convexHull() {
			if($this->length < 3)
				return;
			
			$points = $this->points;
			$sKey = -1;
			foreach ($points as $key => $value) {
				if($sKey == -1)
					$sKey = $key;
				else if(($value->y() < $points[$sKey]->y()) || ($value->y() == $points[$sKey]->y() && $value->x() < $points[$sKey]->x()))
					$sKey = $key;
			}
			$smallest = $points[$sKey];
			array_splice($points	, $sKey, 1);
			
			foreach ($points as $key => $value)
				$points[$key] = Vector::fromVectorDiff($value, $smallest);
			usort($points, function(Vector $a, Vector $b) {
				return sign($a->x()*$b->y() - $b->x()*$a->y());
			});
			$convex = array($points[0], Vector::fromValues(0, 0));
			$i = 1;
			$length = count($points);
			while($i < $length) {
				$first = isset($convex[0])?$convex[0]:false;
				$second = isset($convex[1])?$convex[1]:end($points);
				$current = $points[$i];
				if($current->equalsValues(0, 0)) {
					$i++;
					continue;
				}
				if(!$first || (($second->x()-$first->x())*($current->y()-$first->y())-($current->x()-$first->x())*($second->y()-$first->y())) > 0) {
					array_unshift($convex, $current);
					$i++;
				}
				else
					array_shift($convex);
			}
			
			foreach ($convex as $key => $value)
				$value->add($smallest);
			
			$this->points = $convex;
			$this->length = count($convex);
		}

		public function centroid() {
			if($this->length == 1)
				return $this->points[0];
			if($this->length < 4)
				return $this->average();
			$centroid = Vector::fromValues(0, 0);
			$area = 0;
			for($i = 0; $i < $this->length; $i++) {
				$current = $this->points[$i];
				$next = isset($this->points[$i+1])?$this->points[$i+1]:$this->points[0];
				$areaPart = $current->x() * $next->y() - $next->x() * $current->y();
				$area += $areaPart;
				$centroidPart = Vector::fromVectorSum($current, $next);
				$centroidPart->multiply($areaPart);
				$centroid->add($centroidPart);
			}
			$centroid->divide(3*$area);
			return $centroid;
		}
		
		public function average() {
			$sum = Vector::fromValues(0, 0);
			foreach ($this->points as $key => $value)
				$sum->add($value);
			$sum->divide($this->length);
			return $sum;
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