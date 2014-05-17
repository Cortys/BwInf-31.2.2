<?php
	class Robot {
		private static $counter = 0;
		private $mars;
		private $viewRange;
		private $id;
		private $circle;
		
		public function __construct(Mars $mars, $viewRange) {
			$this->mars = $mars;
			$this->viewRange = is_numeric($viewRange)?$viewRange*1:0;
			$this->circle = array();
			$this->id = self::$counter;
			self::$counter++;
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getViewRange() {
			return $this->viewRange;
		}
		
		public function getCircle() {
			return $this->circle;
		}
		
		public function cycle() {
			$polygon = new Polygon($this->mars->getSurrounding($this));
			$this->circle = $polygon->boundingCircle(!$this->id, $this->mars->queue);
			$center = $this->circle['center'];
			$this->circle['center'] = $this->circle['center']->toArray();
			return $center;
		}
	}
?>