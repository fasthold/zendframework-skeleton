<?php
/**
 * 框架较为低层的配置。一般情况下无需修改。
 * 此文件需加入到VCS中（版本控制），开发时随VCS一同更新。
 */
return
array (
    'error_handler' => 'php', // available options: php, filp/whoops, zend, or a class name of yours
    'version' => '1.0.0', // Application version
    'phpSettings' => array (
        'display_startup_errors' => '1',
        'display_errors' => '1',
        'error_reporting' => -1,
        'log_errors' => 1,
        'error_log' => PATH_ROOT . '/logs/error/php.log', // PHP 错误日志文件
        'date' => array (
            'timezone' => 'Asia/Shanghai',
        ),
    ),
    'bootstrap' => array (
        'path' => PATH_APP . '/Bootstrap.php',
        'class' => 'Bootstrap',
    ),
    'resources' =>  array (
        'frontController' => array (
            'controllerDirectory' => PATH_APP . '/controllers',
            'defaultControllerName' => 'index',
            'defaultAction' => 'index',
        ),
        'view' => array (
            'encoding' => 'UTF-8',
            'basePath' => PATH_APP . '/views',
        ),
        'layout' => array (
            'layout' => 'xhtml',
            'layoutPath' => PATH_APP . '/views/layouts',
        ),
        'db' => array (
            'adapter' => 'PDO_MYSQL',
            'isDefaultTableAdapter' => '1',
            'params' => array (
                'port' => '3306',
                'charset' => 'utf8',
            ),
        ),
        'cachemanager' => array (
            'database' => array (
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
        ),
    ),
    'session' => array (
        'storage' => 'file',
        'name' => 'passport_session_id',
        'use_only_cookies' => '1',
        'strict' => '1',
        'cookie_httponly' => '1',
        'remember_me_seconds' => '86400',
        'lifetime' => '86400',
        'modifiedColumn' => 'modified',
        'dataColumn' => 'data',
        'lifetimeColumn' => 'lifetime',
        'file' => array (
            'save_path' => PATH_RUNTIME . '/sessions',
        ),
        'database' => array (
            'db' => 'db',
            'name' => 'session',
            'primary' => 'id',
            'modifiedColumn' => 'modified',
            'dataColumn' => 'data',
            'lifetimeColumn' => 'lifetime',
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
