<?php
class SQ_Class_Theme extends SQ_Class { 
	public $identifier=null;
	public $xml=null;
	private static $cache=[];
	// ::get does a cached lookup
	public static function get($identifier) { 
		if (array_key_exists($identifier,self::$cache)) { 
			return self::$cache[$identifier];
		}
		return self::$cache[$identifier]=new SQ_Class_Theme($identifier);
	}
	function __construct($identifier) { 
		$this->identifier=$identifier;
		$this->loadXML();
	}
	private function loadXML() { 
		if ($this->xml!=null) { 
	
			return;	
		}
		$this->xml=new SimpleXMLElement(file_get_contents(dirname(__FILE__).'/../themes/'.$this->identifier.'/theme.xml'));
	}
	public function renderPage($page) { 
		include_once('Smarty/Smarty.class.php');
		$templatesmarty=new Smarty();
		$layoutsmarty=new Smarty();
		$templatesmarty->left_delimiter='{!';
		$layoutsmarty->left_delimiter='{!';
		$templatesmarty->right_delimiter='}';
		$layoutsmarty->right_delimiter='}';
		$templatesmarty->setTemplateDir(dirname(__FILE__).'/../themes/'.$this->identifier.'/');
		$layoutsmarty->setTemplateDir(dirname(__FILE__).'/../themes/'.$this->identifier.'/public-layouts/');
		$templatesmarty->setCompileDir(dirname(__FILE__).'/../cache/compiled-templates/');
		$layoutsmarty->setCompileDir(dirname(__FILE__).'/../cache/compiled-templates/');
		$templatesmarty->setCacheDir(dirname(__FILE__).'/../cache/template-cache/');
		$layoutsmarty->setCacheDir(dirname(__FILE__).'/../cache/template-cache/');

		$templatesmarty->assign('PageTitle',$page->Title);
		$templatesmarty->assign('PageLayout',$layoutsmarty->fetch('layout.'.$page->LayoutIdentifier.'.html'));
		//** un-comment the following line to show the debug console
		//$templatesmarty->debugging = true;
		return $templatesmarty->fetch('template.'.$page->TemplateIdentifier.'.html');
	}	
	public static function enumerate() { 
		$ret=array();
		$dir="themes/";
		$themedir=opendir($dir);
		while (($file = readdir($themedir)) !== false) {
			if ($file=='.' || $file=='..') { 
				continue;
			}
			$type=filetype($dir.$file);
			if ($type=='dir') { 
				$theme=new SQ_Class_Theme($file);
				$ret[]=$theme;

			}
		}
		closedir($themedir);
		return $ret;
	}
	public function getInfoBlockHTML($notitle=false,$nodescription=false) { 
		$ret='<article class="theme-infoblock">';
		if (!$notitle) { 
			$ret.='<h1>'.$this->xml->Name.'</h1>';
			$ret.='<small><b>Identifier:</b> '.$this->identifier.'</small>';
		}
		if (!$nodescription && $this->xml->Description) { 
			$ret.='<p>'.$this->xml->Description.'</p>';
		}
		$ret.="</artcle>";
		return $ret;
	}
	public function getTemplateList() { 
		$ret=array();
		for($a=0;$a<$this->xml->Template->count();$a++) { 
			$item=$this->xml->Template[$a];
			$r=array();
			$r['Name']=(string)$item->Name;
			$r['Identifier']=(string)$item->Identifier;
			$r['Description']=(string)$item->Description;
			$r['Default']=(string)$item->Default;
			$ret[]=$r;
		}
		return $ret;
	}
	public function getKeyValueTemplateList() { 
		$list=$this->getTemplateList();
		$ret=array();
		foreach ($list as $item) { 
			$ret[$item['Identifier']]=$item['Name'];
		}
		return $ret;
	}
	public function getLayoutList() { 
		$ret=array();
		for($a=0;$a<$this->xml->Layout->count();$a++) { 
			$item=$this->xml->Layout[$a];
			$r=array();
			$r['Name']=(string)$item->Name;
			$r['Identifier']=(string)$item->Identifier;
			$r['Description']=(string)$item->Description;
			$r['Sections']=array();
			for($b=0;$b<$item->Section->count();$b++) { 
				$sitem=$item->Section[$b];
				$nsitem=array();	
				$nsitem['Identifier']=(string)$sitem->Identifier;
				$nsitem['Name']=(string)$sitem->Name;
				$nsitem['MinWidgets']=(string)$sitem->MinWidgets;
				$nsitem['MaxWidgets']=(string)$sitem->MaxWidgets;
				$r['Sections'][]=$nsitem;
			}
			$ret[]=$r;
		}
		return $ret;
	}
	public function getKeyValueLayoutList() { 
		$list=$this->getLayoutList();
		$ret=array();
		foreach ($list as $item) { 
			$ret[$item['Identifier']]=$item['Name'];
		}
		return $ret;
	}
	private function deactivate($user) { 

	}
	public function activate($user) { 
		$oldtheme=SQ_Class_Theme::get($user->ThemeIdentifier);
		$oldtheme->deactivate($user);
		$user->ThemeIdentifier=$this->identifier;
		$user->save();
	}
} 
