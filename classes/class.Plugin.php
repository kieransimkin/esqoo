<?php
class SQ_Class_Plugin extends SQ_Class { 
	public $identifier=null;
	public $xml=null;
	private static $cache=[];
	// ::get does a cached lookup
	public static function get($identifier) { 
		if (array_key_exists($identifier,self::$cache)) { 
			return self::$cache[$identifier];
		}
		return self::$cache[$identifier]=new SQ_Class_Plugin($identifier);
	}
	public static function enumerate() { 
		$ret=array();
		$dir=dirname(__FILE__)."/../plugins/";
		$plugindir=opendir($dir);
		while (($file = readdir($plugindir)) !== false) {
			if ($file=='.' || $file=='..') { 
				continue;
			}
			$type=filetype($dir.$file);
			if ($type=='dir') { 
				$ret[]=SQ_Class_Plugin::get($file);
			}
		}
		closedir($plugindir);
		return $ret;
	}
	function __construct($identifier) { 
		$this->identifier=$identifier;
		$this->parseXML();
	}
	private function parseXML() { 
		$this->xml=simplexml_load_file($this->getPath().'plugin.xml');
	}
	private function getPath() { 
		return dirname(__FILE__)."/../plugins/".$this->identifier."/";
	}
	function activate($user) { 
		SQ_User_plugin::create(array('user_id'=>$user->id,'Identifier'=>$this->identifier));
		for ($c=0;$c<$this->xml->FrontEnd->URIs->URI->count();$c++) { 
			$this->activate_plugin_uri($this->xml->FrontEnd->URIs->URI[$c],$user);
		}
	}
	private function activate_plugin_uri($urixml,$user) { 
		$uris=array();
		$found=false;
		for ($n=1;$n<100;$n++) { 
			for ($c=0;$c<$urixml->DefaultURIs->DefaultURI->count();$c++) { 
				if ($n==1) { 
					$uri=$urixml->DefaultURIs->DefaultURI[$c];
				} else { 
					$uri=$urixml->DefaultURIs->DefaultURI[$c].$n;
				}
				if (SQ_Class_URL::isURIAvailable($uri,$user)) { 
					$found=true;
					break 2;			
				}
			}
			$n++;
		}
		if (!$found) { 
			throw new Exception('Unable to find a suitable URL for this plugin to use!');
			return;
		}
		SQ_Class_URL::deleteForwardsForURI($uri,$user);
		SQ_Plugin_uri::create(array('user_id'=>$user->id,'URITag'=>$user->Username.'/'.$uri,'PluginIdentifier'=>$this->identifier,'PluginController'=>(string)$urixml->Controller,'Code'=>(string)$urixml->Code));
	}
	function deactivate($user) { 
		$userplugin=SQ_User_plugin::getWhere('user_id=? and Identifier=?',array($user->id,$this->identifier));
		$userplugin->delete();
		$uris=SQ_Plugin_uri::getAll('user_id=? and PluginIdentifier=?',array($user->id,$this->identifier));
		foreach ($uris as $uri) { 
			$uri->delete();
		}
	}
	function getInfoBlockHTML($notitle=false,$nodescription=false) {
		$ret='<article class="plugin-infoblock">';
		if (!$notitle) { 
			$ret.='<h1>'.$this->xml->Name.'</h1>';
			$ret.='<small><b>Identifier:</b> '.$this->identifier.'</small>';
		}
		if (!$nodescription && $this->xml->Description) { 
			$ret.='<p>'.$this->xml->Description.'</p>';
		}
		if ($this->xml->FrontEnd->URIs->URI) { 
			$ret.='<div class="plugin-infoblock-provides-block ui-widget ui-widget-content ui-corner-all ui-state-default">';
			if ($this->xml->FrontEnd->URIs->URI->count()>1) { 
				$ret.=_('This plugin adds').' '.$this->xml->FrontEnd->URIs->URI->count().' '._('folders to your public website.');
			} else { 
				$ret.=_('This plugin adds 1 folder to your public website.');
			}
			$ret.='</div>';
		}
		if ($this->xml->FrontEnd->Sections->Section) { 
			$ret.='<div class="plugin-infoblock-provides-block ui-widget ui-widget-content ui-corner-all ui-state-default">';
			if ($this->xml->FrontEnd->Sections->Section->count()>1) { 
				$ret.=_('This plugin provides').' '.$this->xml->FrontEnd->Sections->Section->count().' '._('widget sections.');
			} else {
				$ret.=_('This plugin provides 1 widget section.');
			}
			$ret.='</div>';
		}
		$ret.="</artcle>";
		return $ret;
	}
} 
