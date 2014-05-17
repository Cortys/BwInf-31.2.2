<?php
	class Queue {
		private $mars;
		private $spaceship;
		private $robots;
		private $positions;
		private $stopped;
		
		public function __construct() {
			$this->robots = array();
			$this->positions = array();
		}
		
		public function setMars(Mars $mars) {
			$this->mars = $mars;
		}
		
		public function setSpaceship(Spaceship $spaceship) {
			$this->spaceship = $spaceship;
		}
		
		public function addRobot(Robot $robot) {
			$this->robots[] = $robot;
		}
		
		public function start($length) {
			$this->stopped = false;
			if(!$this->mars || !$this->spaceship)
				return;
			
			for ($i = 0; $i < $length; $i++) {
				if($this->stopped) {
					$this->positions[] = 'END';
					break;
				}
				$this->spaceship->cycle();
				$movements = array();
				$cache = array();
				foreach ($this->robots as $key => $value) {
					$hash = $this->mars->getRobotPosition($value)->toString().";".$value->getViewRange();
					if(!isset($cache[$hash]))
						$cache[$hash] = $value->cycle();
					$movements[$value->getId()] = $cache[$hash];
				}
				$this->mars->moveRobots($movements);
				$this->positions[] = array("fallen"=>$this->mars->getRobotPositions(), "falling"=>$this->spaceship->getFallingRobotPositions());
			}
		}
		
		public function stop() {
			$this->stopped = true;
		}
		
		public function stopped() {
			return $this->stopped;
		}
		
		public function getPositions() {
			return $this->positions;
		}
	}
?>