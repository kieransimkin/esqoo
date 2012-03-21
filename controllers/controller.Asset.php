<?php
class AssetController extends DetachedController { 
	/****************************************
	 *  ╻ ╻┏━┓╻   ┏━┓┏━╸┏┳┓┏━┓┏━┓┏━┓┏━╸┏━┓  *
	 *  ┃ ┃┣┳┛┃   ┣┳┛┣╸ ┃┃┃┣━┫┣━┛┣━┛┣╸ ┣┳┛  *
	 *  ┗━┛╹┗╸╹   ╹┗╸┗━╸╹ ╹╹ ╹╹  ╹  ┗━╸╹┗╸  *
	 ****************************************/
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
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/

	private function get_picture($uri) { 
		$picture=Picture::get($this->find_uri_id($uri));
		if (substr($uri,0,11)=='/web-small/') { 
			print "1";
			if (is_null($picture->web_small__asset_id)) { 
				Site::loadAndConnect();
				$picture->generate_thumbnail('web-small');
			}
			$picture->web_small__asset->output();
		} else if (substr($uri,0,12)=='/web-medium/') { 
			print "2";
			if (is_null($picture->web_medium__asset_id)) { 
				Site::loadAndConnect();
				$picture->generate_thumbnail('web-medium');
			}
			$picture->web_medium__asset->output();
		} else if (substr($uri,0,11)=='/web-large/') { 
			print "3";
			if (is_null($picture->web_large__asset_id)) { 
				Site::loadAndConnect();
				$picture->generate_thumbnail('web-large');
			}
			$picture->web_large__asset->output();
		} else if (substr($uri,0,14)=='/web-fullsize/') { 
			$picture->web_fullsize__asset->output();
		} else if (substr($uri,0,17)=='/thumbnail-large/') { 
			print "5";
			if (is_null($picture->thumbnail_large__asset_id)) { 
				Site::loadAndConnect();
				$picture->generate_thumbnail('thumbnail-large');
			}
			$picture->thumbnail_large__asset->output();
		} else if (substr($uri,0,17)=='/thumbnail-small/') { 
			print "6";
			if (is_null($picture->thumbnail_small__asset_id)) { 
				Site::loadAndConnect();
				$picture->generate_thumbnail('thumbnail-small');
			}
			$picture->thumbnail_small__asset->output();
		} else if (substr($uri,0,8)=='/square/') { 
			print "7";
			if (is_null($picture->square__asset_id)) { 
				Site::loadAndConnect();
				$picture->generate_thumbnail('square');
			}
			$picture->square__asset->output();
		} else if (substr($uri,0,18)=='/digital-negative/') { 
			$picture->digital_negative__asset->output();
		}
	}
	private function get_video($uri) { 
		$video=Video::get($this->find_uri_id($uri));
	}
	private function get_audio($uri) { 
		$audio=Audio::get($this->find_uri_id($uri));
	}
	private function get_file($uri) { 
		$file=File::get($this->find_uri_id($uri));
		$file->digital_negative__asset->output();
	}
	private function get_asset($uri) {
		$asset=Asset::get($this->find_uri_id($uri));
		$asset->output();
	}
	private function find_uri_id($uri) { 
		$pos=strripos($uri,'/');
		if (!is_numeric(substr($uri,$pos+1,1))) { 
			MVC::throw404();
		}
		return intval(substr($uri,$pos+1));
	}
}
