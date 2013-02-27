<?php

/**
 * $Id$
 */
$start = microtime(true);
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Application configuration file
defined('APPLICATION_CONFIG_FILE')
    || define('APPLICATION_CONFIG_FILE', APPLICATION_PATH . '/configs/framework.ini');

// Custom configuration file
defined('CUSTOM_CONFIG_FILE')
    || define('CUSTOM_CONFIG_FILE', APPLICATION_PATH . '/configs/application.ini');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
)));

require_once APPLICATION_PATH . '/const.php';

// Check if the Zend Framework library installed.
if(!file_exists(APPLICATION_PATH.'/../library/Zend/Application.php')) {
	die("Please download ZendFramework 1.x first, and then put 'Zend' directory into 'library/' .");
}

// Check if the configration file exists.
if(!file_exists(APPLICATION_CONFIG_FILE)) {
	die("Please rename 'application/configs/application.ini-new' to 'application.ini' .");
}

require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

// Read the configuration
$applicationConfig = new Zend_Config_Ini(APPLICATION_CONFIG_FILE, APPLICATION_ENV);
$applicationConfig = $applicationConfig->toArray();
if(empty($applicationConfig)) {
	die("It seems that the framework configs meet some problem.");
}

// If a custom config file exists, load it and override the default configuration
$customConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini');
$customConfig = $customConfig->toArray();

// Combine database configuration
$applicationConfig['resources']['db']['params'] = $customConfig['db']['params'] + $applicationConfig['resources']['db']['params'];

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    $applicationConfig
);

try {
	$application->bootstrap()
		->run();
} catch (Exception $e) {
	echo '<h3>'.$e->getMessage().'</h3>';
	echo "<pre>";
	echo $e->__toString();
	echo "</pre>";
}