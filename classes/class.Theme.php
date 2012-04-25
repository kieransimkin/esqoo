<?php
class Theme { 
	private $identifier=null;
	private $xmlconfig=null;
	function __construct($identifier) { 
		$this->identifier=$identifier;	
	}
	private function loadXML() { 
		if ($this->xmlconfig!=null) { 
			return;	
		}
		$this->xmlconfig=new SimpleXMLElement(file_get_contents(dirname(__FILE__).'/../themes/'.$this->identifier.'/theme.xml'));
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
} 
