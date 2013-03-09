<?php
class SQ_Class_Theme extends SQ_Class { 
	public $identifier=null;
	public $xml=null;
	function __construct($identifier) { 
		$this->identifier=$identifier;	
	}
	private function loadXML() { 
		if ($this->xml!=null) { 
			return;	
		}
		$this->xml=new SimpleXMLElement(file_get_contents(dirname(__FILE__).'/../themes/'.$this->identifier.'/theme.xml'));
	}
	public function renderPage($page) { 
		$this->loadXML();
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
				$theme->loadXML();
				$ret[]=$theme;

			}
		}
		closedir($themedir);
		return $ret;
	}
} 
