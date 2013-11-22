<?php
/**
 * 框架较为低层的配置。一般情况下无需修改。
 * 此文件需加入到VCS中（版本控制），开发时随VCS一同更新。
 */
return
array (
    'version' => '1.0.0', // Application version
    'environment' => 'development', // Runtime Environment. Could be development, production, testing
    'error_handler' => 'php', // available options: php, zend, whoops or a callable class method name of yours
    'php_settings' => array (
        'display_startup_errors' => '1',
        'display_errors' => '1',
        'error_reporting' => -1,
        'log_errors' => 1,
        'error_log' => PATH_ROOT . '/logs/error/php.log', // PHP 错误日志文件
        'date.timezone' => 'Asia/Shanghai',
    ),
    'database' => array (
        'adapter' => 'PDO_MYSQL', // 默认为 MongoDB
        'isDefaultTableAdapter' => '1',
        'params' => array (
            'host' => 'localhost',
            'port' => '3306',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
    ),
    'cachemanager' => array (
        'frontend' => array (
            'name' => 'Core',
            'options' => array (
                'lifetime' => '7200',
                'automatic_serialization' => '1',
            ),
        ),
        'backend' => array (
            'name' => 'File',
            'options' => array (
                'cache_dir' => PATH_RUNTIME . '/caches',
            ),
        ),
    ),
    'session' => array (
        // 'storage' => 'file',
        'name' => 'passport_session_id',
        'use_only_cookies' => 1,
        'strict' => 1,
        'cookie_httponly' => 1,
        'use_only_cookies' => 'on',
        'strict' => 'on',
        'remember_me_seconds' => 86400,
        'cookie_lifetime' => 86400,
        'save_path' => PATH_RUNTIME . '/sessions',
        'save_handler' => 'Base_Session_SaveHandler_MongoDb',
        'save_handler_options' => array(
            'mongodb' => array(
                'connectionString' => 'mongodb://localhost',
                'db' => 'wow',
                'collection' => 'sessions',
            )
        ),
    ),
    'ugc_storage' => array(
        array(
            'type' => 'filesystem',
            'path' => PATH_ROOT . '/ugc/public',
            'url' => '//subdomain.exmaple.com', // 末尾无斜杠。为兼容 http 和 https ，建议url以双斜杠开始
        ),
        // Another storage node below...
        array(

        ),
    ),
    'attachment' => array (
        'sizeLimit' => '204800000',
        'validExtension' => '',
        'invalidExtension' => 'exe,htaccess,bat,sh,shell,php',
        'validMime' => '',
        'invalidMime' => '',
    ),
);
