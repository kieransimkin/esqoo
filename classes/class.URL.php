<?php
class SQ_Class_URL extends SQ_Class { 
	static function isURIAvailable($uri,$user) { 
		$pageuris=SQ_Page_uri::getAll('user_id=? and URITag=?',array($user->id,$user->Username.'/'.$uri));
		if (count($pageuris)>0) { 
			return false;
		}
		$pluginuris=SQ_Plugin_uri::getAll('user_id=? and URITag=?',array($user->id,$user->Username.'/'.$uri));
		if (count($pluginuris)>0) { 
			return false;
		}	
		return true;
	}
	static function deleteForwardsForURI($uri,$user) { 
		$forwards=SQ_Uri_forward::getAll('user_id=? and URITag=?',array($user->id,$user->Username.'/'.$uri));
		foreach ($forwards as $forward) { 
			$forward->delete();
		}
		$pluginforwards=SQ_Plugin_uri_forward::getAll('user_id=? and URITag=?',array($user->id,$user->Username.'/'.$uri));
		foreach ($pluginforwards as $pluginforward) { 
			$pluginforward->delete();
		}
	}
}
