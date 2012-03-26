<?php
class PictureController extends LockedController { 
	/**********************************************
	 *  ╻ ╻┏━┓┏━╸┏━┓   ╻┏┓╻╺┳╸┏━╸┏━┓┏━╸┏━┓┏━╸┏━╸  *
	 *  ┃ ┃┗━┓┣╸ ┣┳┛   ┃┃┗┫ ┃ ┣╸ ┣┳┛┣╸ ┣━┫┃  ┣╸   *
	 *  ┗━┛┗━┛┗━╸╹┗╸   ╹╹ ╹ ╹ ┗━╸╹┗╸╹  ╹ ╹┗━╸┗━╸  *
	 **********************************************/
	function indexUI($arg='',$input=array()) { 

	}
	/*****************************************
	 *  ┏━┓┏━┓╻   ┏━╸╻ ╻┏┓╻┏━╸╺┳╸╻┏━┓┏┓╻┏━┓  *
	 *  ┣━┫┣━┛┃   ┣╸ ┃ ┃┃┗┫┃   ┃ ┃┃ ┃┃┗┫┗━┓  *
	 *  ╹ ╹╹  ╹   ╹  ┗━┛╹ ╹┗━╸ ╹ ╹┗━┛╹ ╹┗━┛  *
	 *****************************************/
	function getrawexifAPI($arg='',$input=array()) { 
		$picture=$this->ensure_api_picture($input);
		if ($this->api_validation_success()) { 
			$picture->add_computed_field('EXIF','get_raw_exif_data');
			$picture->set_visible_api_fields($this->get_picture_fields());
			return $picture;
		}
	}
	function getexifAPI($arg='',$input=array()) { 
		$picture=$this->ensure_api_picture($input);
		if ($this->api_validation_success()) { 
			$picture->add_computed_field('EXIF','get_exif_data');
			$picture->set_visible_api_fields($this->get_picture_fields());
			return $picture;
		}

	}
	/****************************
	 *  ┏━┓┏━┓╻╻ ╻┏━┓╺┳╸┏━╸┏━┓  *
	 *  ┣━┛┣┳┛┃┃┏┛┣━┫ ┃ ┣╸ ┗━┓  *
	 *  ╹  ╹┗╸╹┗┛ ╹ ╹ ╹ ┗━╸┗━┛  *
	 ****************************/
	private function get_picture_fields() {
		return array('id','Name','Description');
	}
	private function ensure_api_picture($input) { 
		$picture=null;
		if (strlen(@$input['PictureID'])<1) { 
			$this->api_error(1,_("PictureID field is required"));
		} else { 
			try { 
				$picture=Picture::get($input['PictureID']);
				if ($picture->album->user_id!=$this->user->id) { 
					$this->api_error(2,_("PictureID not found"));
					$picture=null;
				}
			} catch (DBSQ_Exception $e) { 
				$this->api_error(2,_("PictureID not found"));
			}
			if (is_null($picture)) { 
				$this->api_error(2,_("PictureID not found"));
			}
		}
		return $picture;
	}
} 
