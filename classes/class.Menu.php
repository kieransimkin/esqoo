<?php
class SQ_Class_MenuLeafNode extends SQ_Class { 
	public $title=null;
	public $tooltip=null;
	function __construct($title,$tooltip='') { 
		$this->title=$title;
		$this->tooltip=$tooltip;
	}
}
class SQ_Class_MenuLeafNode_Popup_Buttons extends SQ_Class { 
	public $buttons=array();
	private $_availablebuttons=array('save','close','ok','continue','post','done','cancel');
	function __construct() { 
		$args=func_get_args();
		if (func_num_args()==1 && is_array($args[0])) { 
			foreach ($args[0] as $b) { 
				if (in_array(strtolower($b),$this->_availablebuttons)) { 
					$this->buttons[]=strtolower($b);
				}
			}
		} else if (func_num_args()==1 && is_string($args[0])) { 
			$args[0]=str_replace(" ","",$args[0]);
			$bits=explode(',',$args[0]);
			foreach ($bits as $b) { 
				if (in_array(strtolower($b),$this->_availablebuttons)) { 
					$this->buttons[]=strtolower($b);
				}
			}
		} else { 
			$this->buttons=array('save','close');
		}
	}
	function hasButton($button) { 
		if (in_array(strtolower($button),$this->buttons)) { 
			return true;
		} else { 
			return false;
		}
	}
}
class SQ_Class_MenuLeafNode_Popup extends SQ_Class_MenuLeafNode { 
	public $url=null;
	public $popuptitle=null;
	public $buttons=null;
	public $properties=null;
	function __construct($url,$popuptitle,$buttons,$properties,$title,$tooltip='') { 
		parent::__construct($title,$tooltip);
		$this->url=$url;
		$this->popuptitle=$popuptitle;
		if (!$buttons instanceof SQ_Class_MenuLeafNode_Popup_Buttons) { 
			throw new Exception('$buttons argument to MenuLeafNode_Popup::__construct not an instance of MenuLeafNode_Popup_Buttons');
		}
		$this->buttons=$buttons;
		if (!is_array($properties)) { 
			throw new Exception('$properties argument to MenuLeafNode_Popup::__construct not an array');
		}
		$this->properties=$properties;
	}
	private function getButtonString() { 
		$buttonstring='';
		if (!$this->buttons->hasButton('save')) {
			$buttonstring.="savebutton: 0, ";
		}
		if (!$this->buttons->hasButton('close')) { 
			$buttonstring.="closebutton: 0, ";
		}
		if ($this->buttons->hasButton('ok')) { 
			$buttonstring.="okbutton: 1, ";
		}
		if ($this->buttons->hasButton('continue')) { 
			$buttonstring.="continuebutton: 1, ";
		} 
		if ($this->buttons->hasButton('post')) { 
			$buttonstring.="postbutton: 1, ";
		} 
		if ($this->buttons->hasButton('done')) { 
			$buttonstring.="donebutton: 1, ";
		}
		if ($this->buttons->hasButton('cancel')) { 
			$buttonstring.="cancelbutton: 1, ";
		}
		return $buttonstring;	
	}
	private function getOptionString() { 
		$optionstring='';
		if (array_key_exists('singleton',$this->properties) && $this->properties['singleton']!==false) { 
			$optionstring="singleton: true, ";
		}
		return $optionstring;
	}
	function __toString() { 
		$buttonstring=$this->getButtonString();
		$optionstring=$this->getOptionString();
		$ret = <<<HTML
		<li><a href="{$this->url}" onclick="esqoo_ui.make_dialog({ {$buttonstring} {$optionstring} title: '{$this->title}' },'{$this->url}'); return false;">{$this->title}...</a></li>
HTML;
		return $ret;
	}
	function toHTML5String() { 
		$buttonstring=$this->getButtonString();
		$optionstring=$this->getOptionString();
		$ret = <<<HTML
		<a href="{$this->url}" onclick="esqoo_ui.make_dialog({ {$buttonstring} {$optionstring} title: '{$this->title}' },'{$this->url}'); return false;">{$this->title}...</a>
HTML;
		return $ret;
	}
}
class SQ_Class_MenuLeafNode_TargetBlank extends SQ_Class_MenuLeafNode { 
	public $url=null;
	function __construct($url,$title,$tooltip='') {
		parent::__construct($title,$tooltip);
		$this->url=$url;
	}
	function __toString() { 
		$ret=<<<HTML
		<li><a href="{$this->url}" target="_blank">{$this->title}... </a><div class="nav-float-right"><span class="ui-icon ui-icon-extlink"></span></div></li>
HTML;
		return $ret;
	}
	function toHTML5String() { 
		$ret=<<<HTML
		<a href="{$this->url}" target="_blank">{$this->title}... </a><div class="nav-float-right"><span class="ui-icon ui-icon-extlink"></span></div>
HTML;
		return $ret;
	}
}
class SQ_Class_MenuLeafNode_JSAction extends SQ_Class_MenuLeafNode {
	public $action=null;
	function __construct($action,$title,$tooltip='') {
		parent::__construct($title,$tooltip);
		$this->action=$action;
	}
	function __toString() { 
		$ret=<<<HTML
		<li><a href="#" onclick="{$this->action}; return false;">{$this->title}</a></li>
HTML;
		return $ret;
	}
	function toHTML5String() { 
		$ret=<<<HTML
		<a href="#" onclick="{$this->action}; return false;">{$this->title}</a>
HTML;
		return $ret;
	}
}
class SQ_Class_MenuLeafNode_Go extends SQ_Class_MenuLeafNode { 
	public $url=null;
	function __construct($url,$title,$tooltip='') {
		parent::__construct($title,$tooltip);
		$this->url=$url;
	}
	function __toString() { 
		$ret=<<<HTML
		<li><a href="{$this->url}" onclick="return esqoo_ui.browse_to_new_url($(this).attr('href'));">{$this->title}</a></li>
HTML;
		return $ret;
	}
	function toHTML5String() { 
		$ret=<<<HTML
		<a href="{$this->url}" onclick="return esqoo_ui.browse_to_new_url($(this).attr('href'));">{$this->title}</a>
HTML;
		return $ret;
	}
}
class SQ_Class_Menu extends SQ_Class { 
	private $menuitemcount=0;
	private $menuitems=array();
	public $title=null;
	public $tooltip=null;

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
			$this->menuitemcount=@max(array_keys($this->menuitems))+1;
			$this->title=$args[0];
		} else if (func_num_args()==3 && is_string($args[0]) && is_string($args[1]) && is_array($args[2])) { 
			$this->menuitems=$args[2];
			$this->menuitemcount=@max(array_keys($this->menuitems))+1;
			$this->title=$args[0];
			$this->tooltip=$args[1];
		} else { 
			throw new Exception('Invalid arguments passed to Menu::__construct()');
		}
		if (!is_int($this->menuitemcount)) { 
			$this->menuitemcount=0;
		}
	}
	function __toString() { 
		$ret=<<<HTML
			<ul>
HTML;
		foreach ($this->menuitems as $item) { 
			if ($item instanceof SQ_Class_Menu) { 
				$ret.='<li>';
				$ret.=$item;
				$ret.='</li>';
			} else { 
				$ret.=$item;
			}
		}
		$ret.=<<<HTML
			</ul>
HTML;
		return $ret;
	}
	function toHTML5String() { 
		$ret=<<<HTML
			<nav>
HTML;
		foreach ($this->menuitems as $item) { 
			if ($item instanceof SQ_Class_Menu) { 
				$ret.='<nav>';
				$ret.=$item->toHTML5String();
				$ret.='</nav>';
			} else { 
				$ret.=$item->toHTML5String();
			}
		}
		$ret.=<<<HTML
			</nav>
HTML;
		return $ret;
	}
	function json_export() { 
		return json_encode($this->export());
	}
	function export() { 
		$ret=array();
		$ret['title']=$this->title;
		$ret['tooltip']=$this->tooltip;
		$ret['menuitems']=array();
		foreach ($this->menuitems as $item) { 
			$t=array();
			if ($item instanceof SQ_Class_Menu) { 
				$t=$item->export();
			} else if ($item instanceof SQ_Class_MenuLeafNode_Go) { 
				$t['leaftype']='go';
				$t['url']=$item->url;
				$t['tooltip']=$item->tooltip;
				$t['title']=$item->title;
			} else if ($item instanceof SQ_Class_MenuLeafNode_JSAction) { 
				$t['leaftype']='jsaction';
				$t['action']=$item->action;
				$t['tooltip']=$item->tooltip;
				$t['title']=$item->title;
			} else if ($item instanceof SQ_Class_MenuLeafNode_TargetBlank) { 
				$t['leaftype']='targetblank';
				$t['url']=$item->url;
				$t['tooltip']=$item->tooltip;
				$t['title']=$item->title;
			} else if ($item instanceof SQ_Class_MenuLeafNode_Popup) { 
				$t['leaftype']='popup';
				$t['url']=$item->url;
				$t['popuptitle']=$item->popuptitle;
				$t['tooltip']=$item->tooltip;
				$t['buttons']=$item->buttons->buttons;
				$t['properties']=$item->properties;
				$t['title']=$item->title;
			}
			$ret['menuitems'][]=$t;
		}
		return $ret;
	}
	public function createSubmenu($title,$tooltip=null,$items=array()) { 
		$sub=new SQ_Class_Menu($items);
		$sub->title=$title;
		$sub->tooltip=$tooltip;
		$this->addItem($sub);
		return $sub;
	}
	private function addItem($item) { 
		$this->menuitems[$this->menuitemcount++]=$item;
	}
} 
