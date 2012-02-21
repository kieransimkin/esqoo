<?php
class Controller { 
	public $api_errors=array();
	public function setView($view) {
		$this->view = new View($view);
		$this->view->controller = $this;
	}

	public function render() {
		$this->view->user = $this->user;
		$this->view->jsManager = new JSManager;
		$this->view->jsFiles = $this->jsFiles;
		$this->view->cssManager = new CSSManager;
		$this->view->render();
	}

	private $jsFiles = Array();

	protected function jsFile() {
		foreach(func_get_args() as $file)
			$this->jsFiles[] = $file;
	}
	function api_response($res,$responseformat) { 
		switch ($responseformat) { 
			case 'xml':
				require_once('XML/Serializer.php');
				$options = array(
					"indent"     => "    ",
					"linebreak"  => "\n",
					"addDecl"    => true,
					"defaultTagName"  => "Item",
					"encoding" => "UTF-8",
					"rootName" => "APIResponse"
				);
				$serializer=new XML_Serializer($options);
				$serializer->serialize($res);
				echo $serializer->getSerializedData();
				die;
					
			case 'json':
			default:
				echo json_encode($res);
				die;
		}
	}
	function autoloadJS() {
		$action = $this->action;
		if(strpos($action,'..')!== false || strpos($action,'/')!==false) {
			die("Die hacker!");
		}
		// Autoload JS files for this controller and action 
		if(file_exists(realpath(dirname(__FILE__).'/../js').'/'.$this->controller.'/'.$action.".js") && @$_GET['request_type']!='json') {
			$this->view->head .= "\t<script src=\"/js/{$this->controller}/{$action}.js\"></script>\n";
		} elseif(file_exists(realpath(dirname(__FILE__).'/../js').'/'.$action.".js") && @$_GET['request_type']!='json') {
			$this->view->head .= "\t<script src=\"/js/{$action}.js\"></script>\n";
		}
	}
	function autoloadCSS() {
		$action = $this->action;
		if(strpos($action,'..')!== false || strpos($action,'/')!==false) {
			die("Die hacker!");
		}
		// Autoload JS files for this controller and action 
		if(file_exists(realpath(dirname(__FILE__).'/../css').'/'.$this->controller.'/'.$action.".css") && @$_GET['request_type']!='json') {
			$this->view->head .= "\t<link rel=\"stylesheet\" href=\"/css/{$this->controller}/{$action}.css\" />\n";
		} elseif(file_exists(realpath(dirname(__FILE__).'/../css').'/'.$action.".css") && @$_GET['request_type']!='json') {
			$this->view->head .= "\t<link rel=\"stylesheet\" href=\"/css/{$action}.css\" />\n";
		}
	}
	function api_error($id,$str) { 
		$this->api_errors[]=array('Code'=>$id,'String'=>$str);
	}
	function api_validation_success() { 
		if (count($this->api_errors)>0) { 
			return false;
		} else { 
			return true;
		}
	}
	function __toString() { 
		return get_called_class();
	}

}
