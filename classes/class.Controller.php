<?php
class Controller { 
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


}
