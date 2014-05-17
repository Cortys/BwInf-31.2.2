<?php
	class Circle implements NodeContent {
		
		private $a, $b, $c;
		private $ab, $bc, $ca;
		private $radiusSquare;
		private $angle;
		private $center;
		private $radius;
		private $node;
		
		public function __construct(CircleVector $v) {
			$v->circle($this);
			$this->a = $a = $v->before();
			$this->b = $b = $v;
			$this->c = $c = $v->after();
			if($a === $b && $b === $c) {
				$this->center = Vector::fromVector($a);
				$this->radius = $this->radiusSquare = $this->angle = $this->ab = $this->bc = $this->ca = 0;
			}
			else {
				// Determinante von a, b, c:
				$det = ($c->x()-$a->x())*($c->y()+$a->y())+($b->x()-$c->x())*($b->y()+$c->y())+($a->x()-$b->x())*($a->y()+$b->y());
				// Laengen:
				$this->ab = Vector::fromVectorDiff($a,$b)->lengthSquare();
				$this->bc = Vector::fromVectorDiff($b,$c)->lengthSquare();
				$this->ca = Vector::fromVectorDiff($c,$a)->lengthSquare();
				// Radiusinformation zum Vergleich:
				$this->radiusSquare = $det?bcdiv(bcmul(bcmul($this->ab, $this->bc, 16), $this->ca, 16), 4*$det*$det, 16):0;
				// Winkel:
				$this->angle = acos(($this->ab+$this->bc-$this->ca)/(2*sqrt($this->ab*$this->bc)));
			}
		}
		
		public static function from2Vectors(Vector $a, Vector $b) {
			$a = CircleVector::fromVector($a);
			$b = CircleVector::fromVector($b);
			$a->before($b);
			$a->after($b);
			$b->before($a);
			$b->after($a);
			return new self($a);
		}
		public static function fromVector(Vector $v) {
			$v = CircleVector::fromVector($v);
			$v->before($v);
			$v->after($v);
			return new self($v);
		}
		
		public function __get($property) {
			if(property_exists($this, $property))
				return $this->$property;
		}
		
		public function setNode(Node $n = null) {
			$this->node = $n;
		}
		
		public function before() {
			return $this->a->circle();
		}
		public function after() {
			return $this->c->circle();
		}
		
		public function isSmaller(NodeContent $circle) {
			if(!($circle instanceof Circle))
				return 0;
			$radiusCmp = bccomp($this->radiusSquare, $circle->radiusSquare);
			return $radiusCmp == -1 || ($radiusCmp == 0 && $this->angle < $circle->angle);
		}
		
		public function isFinal() {
			return $this->angle <= M_PI/2;
		}
		
		public function center() {
			if($this->center !== null)
				return $this->center;
			$a = $this->a;
			$b = $this->b;
			$c = $this->c;
			if($a === $c) {
				$v = Vector::fromVectorSum($b, $c);
				$v->divide(2);
				return $v;
			}
			$aL = $a->lengthSquare();
			$bL = $b->lengthSquare();
			$cL = $c->lengthSquare();
			$cbDiff = $c->x()-$b->x();
			$acDiff = $a->x()-$c->x();
			$baDiff = $b->x()-$a->x();
			$divisor = 2*($a->y()*$cbDiff+$b->y()*$acDiff+$c->y()*$baDiff);
			if(!$divisor)
				return Vector::fromValues(0, 0);
			$x = ($aL*($b->y()-$c->y())+$bL*($c->y()-$a->y())+$cL*($a->y()-$b->y()))/$divisor;
			$y = ($aL*$cbDiff+$bL*$acDiff+$cL*$baDiff)/$divisor;
			$this->center = Vector::fromValues($x, $y);
			return $this->center;
		}
		
		public function radius() {
			if($this->radius !== null)
				return $this->radius;
			$this->radius = $this->radiusSquare?sqrt($this->radiusSquare):Vector::fromVectorDiff($this->center(), $this->b)->length();
			return $this->radius;
		}
		
		public function toArray() {
			return array('center'=>$this->center()->toArray(), 'radius'=>$this->radius());
		}
	}
?>