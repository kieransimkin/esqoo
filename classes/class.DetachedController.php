<?php
class DetachedController extends OpenController { 
	private function attach() { 
		Site::loadAndConnect();
	}
}
