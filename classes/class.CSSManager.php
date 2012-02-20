<?php
class CSSManager extends ResourceManager {

	protected $ext = "css";

	function __construct($media="screen") {
		parent::__construct();
		$this->media = $media ? $media : "screen";
	}

	function getPath($file) {
		return $this->approot . '/css/'.$file.'.css';
	}

	function getTag($path) {
		if ($this->media == 'print') {
			return "\t".'<link href="'.$path.'" rel="stylesheet" media="'.$this->media.'"/>'."\n";
		} else {
			return "\t".'<link href="'.$path.'" rel="stylesheet" />'."\n";
		}
	}

	function getContents($path) {
		$base = dirname($path);
		$base = substr($base, strlen($this->approot));

		$css = file_get_contents($path);
		preg_match_all("/url\((.*?)\)/i", $css, $urls);
		foreach ($urls[1] as $n=>$url) {
			$url = trim($url);
			$url = trim($url, "'\"");
			if ($url[0] != "/") { // relative
				$new = $base . "/" . $url;
				//var_dump($urls[0][$n], "url({$new})"); die;
				$css = str_replace($urls[0][$n], "url({$new})", $css);
				//$css = str_replace($url, $new, $css);
			}
		}

		return $css;
	}


	function minify($file) {
		return CssMin::minify($file);
	}
}
