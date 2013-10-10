<?php

/**
 * Application index.php
 */
$start = microtime(true);

// Define path to root
defined('PATH_ROOT')
    || define('PATH_ROOT', realpath(__DIR__ . '/../'));

// Define path to application directory
defined('PATH_APP')
    || define('PATH_APP', PATH_ROOT . '/application');

// Define path to runtime file storage directory
defined('PATH_RUNTIME')
    || define('PATH_RUNTIME', PATH_ROOT . '/runtime');

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Application configuration file
defined('FRAMEWORK_CONFIG_FILE')
    || define('FRAMEWORK_CONFIG_FILE', PATH_APP . '/configs/framework.php');

// Custom configuration file
defined('CUSTOM_CONFIG_FILE')
    || define('CUSTOM_CONFIG_FILE', PATH_APP . '/configs/application.php');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(PATH_APP . '/../library'),
)));

//require_once PATH_APP . '/const.php';
require_once PATH_ROOT . '/vendor/autoload.php';

// Check if the Zend Framework library installed.
if(!class_exists('Zend_Application')) {
	die("Please install vendors first.");
}

// Read the configuration
$applicationConfig = include_once PATH_APP . '/configs/framework.php';

if(empty($applicationConfig)) {
	die("It seems that the framework configs meet some problem.");
}

// If a custom config file exists, load it and override the default configurations.
if(file_exists(CUSTOM_CONFIG_FILE)) {
    $customConfig = include_once PATH_APP . '/configs/application.php';
    // Combine database configuration
    $applicationConfig['resources']['db']['params'] = $customConfig['db']['params'] + $applicationConfig['resources']['db']['params'];
}

$app = new Base\Application();
$app->run();