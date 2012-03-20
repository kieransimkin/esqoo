<?php
class AssetController extends DetachedController { 
	public function remap($uri,$input=array()) { 
		if (substr($uri,0,9)=='/picture/') { 
			$this->get_picture(substr($uri,9));
		} else if (substr($uri,0,7)=='/video/') { 
			$this->get_video(substr($uri,7));
		} else if (substr($uri,0,7)=='/audio/') { 
			$this->get_audio(substr($uri,7));
		} else if (substr($uri,0,6)=='/file/') { 
			$this->get_file(substr($uri,6));
		} else { 
			$this->get_asset(substr($uri,1));	
		}
		die;
	}
	private function get_picture($uri) { 
		print "Getting picture: $uri";
	}
	private function get_video($uri) { 
		print "Getting video: $uri";
	}
	private function get_audio($uri) { 
		print "Getting audio: $uri";
	}
	private function get_file($uri) { 
		print "Getting file: $uri";
	}
	private function get_asset($uri) {
		print "Getting Asset: $uri";
	}
}
