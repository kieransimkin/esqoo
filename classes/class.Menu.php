<?php
class MenuLeafNode { 
	private $title=null;
	private $tooltip=null;
	function __construct($title,$tooltip='') { 
		$this->title=$title;
		$this->tooltip=$tooltip;
	}
}
class MenuLeafNode_Popup extends MenuLeafNode{ 
	
	function __toString($url,$popuptitle,$buttons,$properties,$title,$tooltip='') { 
		parent::__construct	
	}
}
class MenuLeafNode_TargetBlank extends MenuLeafNode { 
	private $url=null;
	function __construct($url,$title,$tooltip='') {
		parent::__construct($title,$tooltip);
		$this->url=$url;
	}
	function __toString() { 

	}
}
class MenuLeafNode_JSAction extends MenuLeafNode {
	private $action=null;
	function __construct($action,$title,$tooltip='') {
		parent::__construct($title,$tooltip);
		$this->$action=$action;
	}
	function __toString() { 

	}
}
class MenuLeafNode_Go extends MenuLeafNode { 
	private $url=null;
	function __construct($url,$title,$tooltip='') {
		parent::__construct($title,$tooltip);
		$this->url=$url;
	}
	function __toString() { 

	}
}
class Menu { 
	private $menuitemcount=0;
	private $menuitems=array();
	private $title=null;
	private $tooltip=null;

//	function __construct(array $items) --
//	 one argument is an array of items

//	function __construct(string $title, array $items) -- 
//	 two arguments is a top level title for the menu followed by its items.

// 	function __construct(string $title, string $tooltip, array $items) -- 
//	 three arguments is a top level title, a top level tooltip and an array of items

// 	$items must be an array containing only instances of classes that extend MenuLeafNode and
//	instances of the Menu class itself (for submenus)
	function __construct() { 
		$args=func_get_args();
		if(func_num_args()==1 && is_array($args[0])) { 
			$this->menuitems=$args[0];
			$this->menuitemcount=max(array_keys($this->menuitems))+1;
		} else if (func_num_args()==2 && is_string($args[0]) && is_array($args[1])) { 
			$this->menuitems=$args[1];
			$this->menuitemcount=max(array_keys($this->menuitems))+1;
			$this->title=$args[0];
		} else if (func_num_args()==3 && is_string($args[0]) && is_string($args[1]) && is_array($args[2])) { 
			$this->menuitems=$args[2];
			$this->menuitemcount=max(array_keys($this->menuitems))+1;
			$this->title=$args[0];
			$this->tooltip=$args[1];
		} else { 
			throw new Exception('Invalid arguments passed to Menu::__construct()');
		}
	}
	function __toString() { 

	}
	public function createSubmenu($title,$tooltip=null,$items=array()) { 
		$sub=new Menu($items);
		$sub->title=$title;
		$sub->tooltip=$tooltip;
		$this->addItem($sub);
		return $sub;
	}
	private function addItem($item) { 
		$this->menuitems[$this->menuitemcount++]=$item;
	}
} 
