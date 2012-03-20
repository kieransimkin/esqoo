<?php
class AssetController extends DetachedController { 
	public function remap($uri,$input=array()) { 
		if (substr($uri,0,9)=='/picture/') { 
			$this->get_picture(substr($uri,8));
		} else if (substr($uri,0,7)=='/video/') { 
			$this->get_video(substr($uri,6));
		} else if (substr($uri,0,7)=='/audio/') { 
			$this->get_audio(substr($uri,6));
		} else if (substr($uri,0,6)=='/file/') { 
			$this->get_file(substr($uri,5));
		} else { 
			$this->get_asset($uri);	
		}
		die;
	}
	private function get_picture($uri) { 
		print "Getting picture: $uri";
	}
	private function get_video($uri) { 
		print "Getting video: ".$this->find_uri_id($uri);
	}
	private function get_audio($uri) { 
		print "Getting audio: ".$this->find_uri_id($uri);
	}
	private function get_file($uri) { 
		print "Getting file: ".$this->find_uri_id($uri);
	}
	private function get_asset($uri) {
		print "Getting Asset: ".$this->find_uri_id($uri);
	}
	private function find_uri_id($uri) { 
		$pos=strripos($uri,'/');
		print substr($uri,$pos+1);
		if (!is_numeric(substr($uri,$pos+1,1))) { 
			MVC::throw404();
		}
		return (int)substr($uri,pos);
	}
}
