<?php
class AssetController extends DetachedController { 
	public function remap($uri,$arg='',$input=array()) { 
		var_dump(array($uri,$arg,$input));
	}
}
