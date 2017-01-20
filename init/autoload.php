<?php

function classLoader($class) {
	$filePath = dirname(__FILE__);

	if(file_exists($filePath . '/' . $class . '.Class.php')){
	include_once $filePath . '/' . $class . '.Class.php';
	}
	if(file_exists($filePath . '/controller/' . $class . '.Class.php')){
	include_once $filePath . '/controller/' . $class . '.Class.php';
	}
	if(file_exists($filePath . '/model/' . $class . '.Class.php')){
	include_once $filePath . '/model/' . $class . '.Class.php';
	}
	if(file_exists($filePath . '/lib/' . $class . '.Class.php')){
	include_once $filePath . '/lib/' . $class . '.Class.php';
	}

}