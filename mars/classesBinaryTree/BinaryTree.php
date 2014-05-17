<?php
	class Node {
		protected $parent = 0;
		protected $left = 0;
		protected $right = 0;
		protected $content = 0;
	 	
	 	public function __construct(NodeContent $content) {
	 		$this->content = $content;
	 		$content->setNode($this);
	 	}
	 	
	 	public function left($n = null) {
	 		if($n !== null) {
	 			$this->left = $n;
	 			if($n)
	 				$n->parent($this);
	 		}
	 		return $this->left;
	 	}
	 	
	 	public function right($n = null) {
	 		if($n !== null) {
	 			$this->right = $n;
	 			if($n)
	 				$n->parent($this);
	 		}
	 		return $this->right;
	 	}
	 	
	 	public function parent($n = null) {
	 		if($n !== null)
	 			$this->parent = $n;
	 		return $this->parent;
	 	}
	 	
	 	public function content(NodeContent $c = null) {
	 		if($c !== null) {
	 			$this->content = $c;
	 			$c->setNode($this);
	 		}
	 		return $this->content;
	 	}
	 	
	 	public function isSmaller(Node $node) {
	 		return $this->content->isSmaller($node->content());
	 	}
	 	public function isLeaf() {
	 		return !$this->left && !$this->right;
	 	}
	 	public function isRoot() {
	 		return !$this->parent;
	 	}
	}
	
	interface NodeContent {
		public function isSmaller(NodeContent $content);
		public function setNode(Node $node);
	}
	 
	class BinaryTree {
		protected $_root = null;
		protected $length = 0;
	 	
	 	public function length() {
	 		return $this->length;
	 	}
	 	
		protected function _insert(Node $new, Node $node = null) {
			if(!$this->_root)
				$this->_root = $new;
			else if($new->isSmaller($node)) {
				if(!$node->left())
					$node->left($new);
				else
					$this->_insert($new, $node->left());
			}
			else {
				if(!$node->right())
					$node->right($new);
				else
					$this->_insert($new, $node->right());
			}	
		}
	 
		public function insert(Node $node) {
			$this->length++;
			$this->_insert($node, $this->_root);
		}
		
		public function delete(Node $node) {
			$this->length--;
			$node->content()->setNode(null);
			
			$child = null;
			if($node->isLeaf())
				$child = 0;
			else if(!$node->left())
				$child = $node->right();
			else if(!$node->right())
				$child = $node->left();
			else {
				$child = $node->right();
				$tmp = $node;
				while($child->left()) {
					$tmp = $child;
					$child = $child->left();
				}
				$child->left($node->left());
				if($tmp !== $node) {
					$tmp->left($child->right());
					$child->right($node->right());
				}
			}
			
			if($node->isRoot()) {
				if(!$child)
					$child = null;
				else
					$child->parent(0);
				$this->_root = $child;
			}
			else if($node->parent()->left() === $node)
				$node->parent()->left($child);
			else
				$node->parent()->right($child);
		}
	 	
	 	protected function _toString(Node $n, $i) {
	 		error_log($i.":".rad2deg($n->content()->angle)." - ".($n->isRoot()*1)." - ".($n->isLeaf()*1));
	 		if($n->left())
	 			$this->_toString($n->left(), $i.'l');
	 		if($n->right())
	 			$this->_toString($n->right(), $i.'r');
	 	}
	 	
	 	public function show() {
	 		if($this->_root)
	 			$this->_toString($this->_root, '-');
	 	}
	 	
	 	protected function _largest(Node $node) {
	 		if(!$node->right())
	 			return $node;
	 		return $this->_largest($node->right());
	 	}
	 	
		public function largest() {
			if(!$this->_root)
				return null;
			return $this->_largest($this->_root);
		}
	}
?>