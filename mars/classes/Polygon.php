<?php
	class Polygon {
		protected $points;
		protected $length;
		
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
			array_splice($points, $sKey, 1);
			
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
		
		public function boundingCircle() {
			$this->convexHull();
			$points = $this->points;
			if(!$this->length)
				return Circle::fromVector(Vector::fromValues(0, 0));
			if($this->length == 1)
				return Circle::fromVector($points[0]);
			if($this->length == 2)
				return Circle::from2Vectors($points[0], $points[1]);
			
			foreach ($points as $key => $value)
				$points[$key] = CircleVector::fromVector($value);
			
			$heap = new CircleHeap();
			
			$l = $this->length;
			foreach($points as $i => $curr) {
				$curr->before($points[($i-1)+(($i-1)<0?$l:0)]);
				$curr->after($points[($i+1)%$l]);
				$heap->insert(new Circle($curr));
			}
			$circle = null;
			$i = 0;
			
			while(true) {
				$largest = $heap->largest();
				$i++;
				if($i > $l || $largest->isFinal())
					break;
				$heap->delete($largest);
				$heap->delete($largest->before());
				$heap->delete($largest->after());
								
				$largest->a->after($largest->c);
				$largest->c->before($largest->a);
				$heap->insert(new Circle($largest->a));
				$heap->insert(new Circle($largest->c));
			}
			return $largest;
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