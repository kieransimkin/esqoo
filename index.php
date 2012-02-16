<?php

date_default_timezone_set('UTC');
error_reporting(E_ALL);
ob_start(); // so we can chuck the output when doing AJAX JSON responses


ini_set('include_path',dirname(__FILE__)."/libraries/");

function __autoload($class_name) {
	$files = Array(
		dirname(__FILE__)."/models/model.{$class_name}.php",
		dirname(__FILE__)."/controllers/controller.{$class_name}.php",
		dirname(__FILE__)."/classes/class.{$class_name}.php",
	);
	foreach ($files as $file)
		if (file_exists($file))
			return include_once($file);
}


function shutdown_function () {
	if (is_null($e = error_get_last()) === false) {
		if ($e['type']==E_ERROR || $e['type']==E_PARSE || $e['type']==E_CORE_ERROR || $e['type']==E_CORE_WARNING || $e['type']==E_COMPILE_ERROR || $e['type']==E_COMPILE_WARNING) { 
			header("HTTP/1.1 500 Internal Server Error");
			return false;
		}
	}
}
function error_handler_function ($errno, $errstr, $errfile, $errline, $errcontext) { 
	return FALSE;
}
set_error_handler('error_handler_function', E_ALL);
register_shutdown_function('shutdown_function'); 

MVC::dispatch($_SERVER['REQUEST_URI']);
