<?php
class SQ_Class_ResourceManager extends SQ_Class {

	function __construct() {
		$this->config = Array( // XXX FIXME make this part of the config
			"concat" => 0,
			"minify" => 0,
			"resources_version" => false, // XXX this should be part of the codebase
			"parallelise_resources" => false,
		);
		$this->approot = SQ_Class_Site::$approot;

		$this->dir = $this->approot . "/compiled-resources";
		$this->webdir = "/compiled-resources";
		if (!file_exists($this->dir) && !mkdir($this->dir, 0755, true))
			throw new Exception("Could not create resources compile directory '{$this->dir}'");
		foreach (func_get_args() as $file)
			$this->add($file);
	}

	private $files = Array();

	public function add() {
		$files = func_get_args();
		foreach ($files as &$file) {
			if (!is_array($file))
				$file = Array($file);
			foreach ($file as &$_file)
				$this->files[] = $_file;
		}
		$this->files = array_unique($this->files);
	}

	public function display() {
		$ext = (@$this->config['resources_version'] ? "-".$this->config['resources_version'] : '') . '.' . $this->ext;
		if (!$this->config['concat']) {
			$r = Array();
			foreach ($this->files as $file) {
				$path = $this->getPath($file);
				$filename = str_replace("/", "_", $file) . $ext;
				$compiled_path = $this->dir . "/" . $filename;

				$max_mtime = filemtime($path);
				$comp_mtime = @filemtime($compiled_path);

				if ($max_mtime != $comp_mtime) {
					$contents = file_get_contents($path);
					if ($this->config['minify'])
						$contents = $this->minify($contents);
					$tmp = tempnam($this->dir, "resource");
					file_put_contents($tmp, $contents);
					chmod($tmp, 0644);
					touch($tmp, $max_mtime);
					rename($tmp, $compiled_path);
				}

				$r[] = $this->getTag("{$this->webdir}/{$filename}");
			}
			echo implode("", $r);
            $this->files = Array();
			return;
		}
		$basename = implode(",", $this->files);
		if (strlen($basename) + strlen($ext) > 255)
			$filename = sha1($basename) . $ext;
		else
			$filename = $basename . $ext;

		$filename = str_replace("/", "_", $filename);

		$compiled_path = $this->dir . "/" . $filename;

		$paths = array_map(Array($this, "getPath"), $this->files);
		foreach ($paths as &$path)
			if (!file_exists($path))
				throw new Exception("Resource not found {$path}");

		$max_mtime = max(array_map("filemtime", $paths));
		$comp_mtime = @filemtime($compiled_path);

		if ($max_mtime != $comp_mtime) { // file has changed
		//	die("compiling");
			$concatenated = '';
			foreach ($paths as &$path)
				$concatenated .= $this->getContents($path) . "\r\n\r\n";
			if ($this->config['minify'])
				$concatenated = trim($this->minify($concatenated));
			$tmp = tempnam($this->dir, "resource");
			file_put_contents($tmp, $concatenated);
			chmod($tmp, 0644);
			touch($tmp, $max_mtime);
			rename($tmp, $compiled_path);
			unset($concatenated);
		}

		$this->files = Array();

		echo $this->getTag("{$this->webdir}/{$filename}");
	}

	function getContents($path) {
		return file_get_contents($path);
	}

	function stringhash($string) {
		$strlen = strlen($string);
		$ord = 0;
		for ($n = 0; $n < $strlen; $n++)
			$ord += ord($string[$n]);
		return $ord;
	}
}
