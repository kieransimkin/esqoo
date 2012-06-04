<?php
class SQ_Class_JSManager extends SQ_Class_ResourceManager {

	protected $ext = "js";

	function getPath($file) {
		return $this->approot . '/js/'.$file.'.js';
	}

	function getTag($path) {
		return "\t".'<script src="'.$path.'"></script>'."\n";
	}

	function minify($file) {
		return SQ_Class_JSMin::minify($file);
	}
}
