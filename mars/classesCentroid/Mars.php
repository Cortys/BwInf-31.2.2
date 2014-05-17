<?php
	class Mars {
		private $queue;
		private $robots;
		private $size;
		private $finalRobotNum;
		
		public function __construct() {
			$this->robots = array();
			$this->size = Vector::fromValues(0, 0);
			$this->finalRobotNum = 0;
		}
		
		public function setFinalRobotNum($num) {
			if(is_numeric($num))
				$this->finalRobotNum = $num;
		}
		
		public function setQueue(Queue $queue) {
			$this->queue = $queue;
		}
		
		public function setFallenRobots(array $robots = null) {
			if($robots)
				foreach ($robots as $key => $value)
					if(is_array($value)) {
						$robot = new Robot($this, $value['range']*1);
						$this->addRobot($robot, $value['x'], $value['y']);
					}
		}
		
		public function setSize($width, $height) {
			if(is_numeric($width) && is_numeric($height))
				$this->size = Vector::fromValues($width, $height);
		}
		
		public function getSize() {
			return $this->size;
		}
		
		public function addRobot(Robot $robot, $x, $y) {
			if(is_numeric($x) && is_numeric($y)) {
				$this->robots[$robot->getId()] = array('robot'=>$robot, 'position'=>Vector::fromValues($x, $y));
				$this->queue->addRobot($robot);
			}
		}
		
		public function moveRobots(array $movements) {
			$end = true;
			foreach ($movements as $key => $value) {
				if($value->lengthSquare() > 1) {
					$value->normalize();
					$end = false;
				}
				$this->robots[$key]['position']->add($value);
			}
			if($end && count($movements) == $this->finalRobotNum)
				$this->queue->stop();
		}
		
		public function getRobotPosition(Robot $robot) {
			return $this->robots[$robot->getId()]['position'];
		}
		
		public function getRobotPositions() {
			$result = array();
			foreach ($this->robots as $key => $value) {
				$a = $value['position']->toArray();
				$a['range'] = $value['robot']->getViewRange();
				$result[] = $a;
			}
			return $result;
		}
		
		public function getSurrounding(Robot $robot) {
			$rangeSquare = $robot->getViewRange();
			$rangeSquare *= $rangeSquare;
			$id = $robot->getId();
			$pos = $this->robots[$id]['position'];
			
			$surrounding = array();
			foreach ($this->robots as $key => $value) {
				if($key == $id)
					continue;
				$rel = Vector::fromVectorDiff($value['position'], $pos);
				if($rel->lengthSquare() <= $rangeSquare)
					$surrounding[] = $rel;
			}
			return $surrounding;
		}
	}
?>