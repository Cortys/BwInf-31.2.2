<?php
	
	/****************************************************************
	 * 31. Bundeswettbewerb Informatik								*
	 * Autor:			Clemens Damke								*
	 * Aufgabe:			-											*
	 * Datei:			helper.php									*
	 * Beschreibung:	Allgemeine Funktionen und Klassen			*
	 * Datum: 			7. Februar 2013								*
	 ****************************************************************/
	
	function randomSort($a, $b) {
		return mt_rand(0, 2)-1;
	}
	
	function rotateArray(&$array, $shift) {
		for ($i = 0; $i < $shift; $i++)
			$array[] = array_shift($array);
	}
	
	function sign($number) {
		return ($number>0) - ($number<0);
	}
	
	class Vector {
		private $x;
		private $y;
		
		protected function __construct($x=0, $y=0) {
			$this->x = $x*1;
			$this->y = $y*1;
		}
		
		public static function fromValues($x, $y) {
			return new self($x, $y);
		}
		
		public static function fromVector(Vector $v) {
			return new self($v->x(), $v->y());
		}
		
		public static function fromVectorSum(Vector $v1, Vector $v2) {
			return new self($v1->x()+$v2->x(), $v1->y()+$v2->y());
		}
		
		public static function fromVectorDiff(Vector $v1, Vector $v2) {
			return new self($v1->x()-$v2->x(), $v1->y()-$v2->y());
		}
		
		public function x($new = null) {
			if($new !== null)
				$this->x = $new*1;
			return $this->x;
		}
		
		public function y($new = null) {
			if($new !== null)
				$this->y = $new*1;
			return $this->y;
		}
		
		public function add(Vector $v) {
			$this->x += $v->x();
			$this->y += $v->y();
		}
		
		public function sub(Vector $v) {
			$this->x -= $v->x();
			$this->y -= $v->y();
		}
		
		public function multiply($scalar) {
			$this->x *= $scalar;
			$this->y *= $scalar;
		}
		
		public function divide($scalar) {
			if(!$scalar)
				return;
			$this->x /= $scalar;
			$this->y /= $scalar;
		}
		
		public function equals(Vector $v) {
			return $this->x == $v->x() && $this->y == $v->y();
		}
		
		public function equalsValues($x, $y) {
			return $this->x == $x && $this->y == $y;
		}
		
		public function toArray() {
			return array("x"=>$this->x(), "y"=>$this->y());
		}
		public function toString() {
			return round($this->x).";".round($this->y);
		}
		
		public function lengthSquare() {
			return $this->x*$this->x + $this->y*$this->y;
		}
		
		public function length() {
			return sqrt($this->lengthSquare());
		}
		
		public function normalize() {
			$this->divide($this->length());
		}
	}
?>