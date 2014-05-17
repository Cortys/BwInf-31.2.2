<?php
	class CircleHeap extends SplMaxHeap {
		
		public function compare($c1, $c2) {
			if(!($c1 && $c2 && $c1 instanceof Circle && $c2 instanceof Circle))
				return 0;
			return $c1->isSmaller($c2)?-1:1;
		}
		
		public function delete(Circle $c = null) {
			if(!$c)
				return;
			if($c === $this->largest())
				$this->extract();
			else
				$c->delete();
		}
		
		public function largest() {
			if($this->isEmpty())
				return null;
			$c = $this->top();
			while($c->deleted) {
				$this->extract();
				$c = $this->top();
			}
			return $c;
		}
	}
?>