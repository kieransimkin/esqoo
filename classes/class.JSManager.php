<?php
class JSManager extends ResourceManager {

	protected $ext = "js";

	function getPath($file) {
		return $this->approot . '/js/'.$file.'.js';
	}

	function getTag($path) {
		return "\t".'<script src="'.$path.'"></script>'."\n";
	}

	function minify($file) {
		return JSMin::minify($file);
	}
}
