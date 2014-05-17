<?php
	class Spaceship {
		private $mars; // Mars-Objekt
		private $robotViewRange; // Sichtweite der abzuwerfenden Roboter
		private $robotNum; // Anzahl der Roboter
		private $fallingRobots; // Array von fallenden Robotern
		private $nextThrow; // Zeitschritte bis zum naechsten Abwurf
		private $distribution; // Streuungs-Faktor fuer den Abwurf
		private $maxThrowDelay; // Maximale Verzoegerung zwischen den Abwuerfen
		private $robotFallDuration; // Falldauer eines Roboters
		
		public function __construct() {
			$this->robotNum = 0;
			$this->robotViewRange = 0;
			$this->nextThrow = 1;
			$this->distribution = 0.5;
			$this->maxThrowDelay = 10;
			$this->robotFallDuration = 5;
			$this->fallingRobots = array_fill(0, $this->robotFallDuration, null);
		}
		
		public function setMars(Mars $mars) {
			$this->mars = $mars;
		}
		
		public function setRobotViewRange($range) {
			if(is_numeric($range))
				$this->robotViewRange = $range;
		}
		
		public function setRobotNum($num) {
			if(is_integer($num))
				$this->robotNum = $num;
		}
		
		public function setFallingRobots(array $robots = null) {
			if($robots)
				foreach ($robots as $key => $value)
					if(is_array($value)) {
						$robot = new Robot($this->mars, $value['range']*1);
						$this->fallingRobots[$value['height']] = array('robot'=>$robot, 'position'=>Vector::fromValues($value['x'], $value['y']));
					}
		}
		
		public function setDistribution($distribution) {
			if(is_numeric($distribution))
				$this->distribution = $distribution;
		}
		
		public function setRobotFallDuration($duration) {
			if(is_integer($duration)) {
				$this->robotFallDuration = $duration;
				$this->maxThrowDelay = $duration*2;
			}
		}
		
		private function generatePosition($positions, $l) {
			$position = $positions[mt_rand(0, $l-1)];
			$angle = deg2rad(mt_rand(0,360));
			$maxDistance = max($position['range']-$this->robotFallDuration, 0);
			$minDistance = $maxDistance*$this->distribution;
			$distance = mt_rand($minDistance, min($maxDistance*($this->distribution+0.5), $maxDistance));
			$x = (cos($angle)*$distance)+$position['x'];
			$y = (sin($angle)*$distance)+$position['y'];
			return Vector::fromValues($x, $y);
		}
		
		private function throwRobot() {
			$positions = $this->mars->getRobotPositions();
			$size = $this->mars->getSize();
			if(($l = count($positions)) != 0)
				do {
					$pos = $this->generatePosition($positions, $l);
				} while($pos->x() < 0 || $pos->y() < 0 || $pos->x() > $size->x() || $pos->y() > $size->y());
			else if(count($positions = $this->getFallingRobotPositions()) > 0)
				$pos = $this->generatePosition($positions, 1);
			else
				$pos = Vector::fromValues($size->x()/2, $size->y()/2);
			$robot = new Robot($this->mars, $this->robotViewRange);
			if($this->robotFallDuration <= 0)
				$this->mars->addRobot($robot, $pos->x(), $pos->y());
			else
				$this->fallingRobots[$this->robotFallDuration-1] = array('robot'=>$robot, 'position'=>$pos);
		}
		
		public function cycle() {
			$nextRobot = array_shift($this->fallingRobots);
			if($nextRobot) {
				$x = $nextRobot['position']->x();
				$y = $nextRobot['position']->y();
				$this->mars->addRobot($nextRobot['robot'], $x, $y);
			}
			$this->fallingRobots[] = null;
			if($this->robotNum <= 0 || (--$this->nextThrow) > 0)
				return;
			if($this->robotFallDuration > 0) {
				$this->robotNum--;
				$this->nextThrow = rand(1, $this->maxThrowDelay);
				$this->throwRobot();
			}
			else {
				for($i = 0; $i<$this->robotNum; $i++)
					$this->throwRobot();
				$this->robotNum = 0;
			}
		}
		
		public function getFallingRobotPositions() {
			$result = array();
			foreach ($this->fallingRobots as $key => $value)
				if($value) {
					$a = $value['position']->toArray();
					$a['range'] = $value['robot']->getViewRange();
					$a['height'] = $key;
					$result[] = $a;
				}
			return $result;
		}
	}
?>