<?php
class DetachedController extends OpenController { 
	private $detached=true;
	private function attach() { 
		Site::loadAndConnect();
		$this->detached=false;
	}
}
