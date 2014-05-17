<?php
	class Robot {
		private static $counter = 0;
		private $mars;
		private $viewRange;
		private $id;
		
		public function __construct(Mars $mars, $viewRange) {
			$this->mars = $mars;
			$this->viewRange = is_numeric($viewRange)?$viewRange*1:0;
			$this->id = self::$counter;
			self::$counter++;
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getViewRange() {
			return $this->viewRange;
		}
		
		public function cycle() {
			$polygon = new Polygon($this->mars->getSurrounding($this));
			$polygon->convexHull();
			return $polygon->centroid();
		}
	}
?>