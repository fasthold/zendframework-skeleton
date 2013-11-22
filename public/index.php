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

if(!is_readable(PATH_ROOT . '/vendor/autoload.php')) {
    die('Please install <a href="//getcomposer.org" target="_blank">Composer</a> and vendors first.');
}

$autoloader = include PATH_ROOT . '/vendor/autoload.php';
$autoloader->add('', __DIR__.'/../application/modules', false);

// Check if the Zend Framework library installed.
if(!class_exists('Zend_Application')) {
	die("Please install vendors first.");
}

// Read the configuration
$applicationConfig = include_once PATH_APP . '/configs/framework.php';

if(empty($applicationConfig)) {
	die("It seems that the framework configurations meet some problem.");
}

// If a custom config file exists, load it and override the default configurations.
if(file_exists(CUSTOM_CONFIG_FILE)) {
    $customConfig = include_once PATH_APP . '/configs/application.php';
    $applicationConfig = array_merge($applicationConfig, $customConfig);
}

$app = new Base\Application($applicationConfig);
$app->run();
