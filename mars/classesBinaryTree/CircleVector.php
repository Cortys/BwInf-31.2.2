<?php
	class CircleVector extends Vector {
		private $before = null;
		private $after = null;
		private $circle = null;
		
		public static function fromVector(Vector $v) {
			return new self($v->x(), $v->y());
		}
		
		public function before(CircleVector $v = null) {
			if($v !== null)
				$this->before = $v;
			return $this->before;
		}
		
		public function after(CircleVector $v = null) {
			if($v !== null)
				$this->after = $v;
			return $this->after;
		}
		
		public function circle(Circle $c = null) {
			if($c !== null)
				$this->circle = $c;
			return $this->circle;
		}
	}
?>