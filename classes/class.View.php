<?php
class View { 
	public $template='normal';
	function __construct($view_name) {
		$this->view_name = $view_name;
	}
	function render() {
		if(isset($_REQUEST['request_type'])&&$_REQUEST['request_type']=='ajax') {
			header("Content-type: application/json");

			// Sanitize $this before sending it
			$output = array();
			foreach($this as $k => &$v) {
				if(!in_array($k,array("user","controller", "form","options"))) {
					$output[$k]=$v;
				}
			}

			print json_encode($output); die;
		} else {
			$this->partial($this->view_name);
		}
	}
	function setTemplate($template) { 
		$this->template=$template;
	}
	function partial($name) {
		$file = Site::$approot . "/views/" . $name;
		if(file_exists($file)) {
			include($file);
		} else {
			die($file.' does not exist!');
		}
	}
	function action($controller,$action) {
		MVC::dispatch($controller,$action);
	}
	function header($title) {
		$this->title = $title;
		$this->partial("partials/view.header.php");
		$this->partial("partials/templates/template.".$this->template.".header.php");
	}
	function footer() {
		$this->partial("partials/templates/template.".$this->template.".footer.php");
		$this->partial("partials/view.footer.php");
	}
} 
