<?php

date_default_timezone_set('UTC');
ini_set('include_path',dirname(__FILE__)."/libraries/");
require_once(dirname(__FILE__)."/classes/class.SQ_Class.php");
spl_autoload_register(function($class_name) {
	$files=array();
	if (substr($class_name,0,9)=='SQ_Class_') { 
		$classlessname=substr($class_name,9);
		$files = Array(
			dirname(__FILE__)."/classes/class.{$classlessname}.php"
		);
	} else if (substr($class_name,0,14)=='SQ_Controller_') { 
		$controllerlessname=substr($class_name,14);
		$files[]=dirname(__FILE__)."/controllers/controller.{$controllerlessname}.php";
	} else if (substr($class_name,0,3)=='SQ_') { 
		$sqlessname=substr($class_name,3);
		$files=array(dirname(__FILE__)."/models/model.{$sqlessname}.php");
	}
	foreach ($files as $file)
		if (file_exists($file))
			return include_once($file);

	//throw new Exception('Class "'.$class_name.'" could not be autoloaded');
});


function SQ_shutdown_function () {
	if (is_null($e = error_get_last()) === false) {
		if ($e['type']==E_ERROR || $e['type']==E_PARSE || $e['type']==E_CORE_ERROR || $e['type']==E_CORE_WARNING || $e['type']==E_COMPILE_ERROR || $e['type']==E_COMPILE_WARNING) { 
			var_dump($e);
			header("HTTP/1.1 500 Internal Server Error");
			return false;
		}
	}
}
function SQ_error_handler_function ($errno, $errstr, $errfile, $errline, $errcontext) { 
	return FALSE;
}
set_error_handler('SQ_error_handler_function', E_ALL);
register_shutdown_function('SQ_shutdown_function'); 

SQ_Class_MVC::dispatch($_SERVER['REQUEST_URI']);
