<?php

/**
 * This file is part of ZendFramework skeleton.
 */

namespace Base;

use Zend_Controller_Front as Front;
use Zend_Controller_Router_Route as Route;
use Zend_Layout;
use Zend_Session as Session;
use Whoops\Run as Whoops;
use Whoops\Handler\PrettyPageHandler;
use Base\Bootstrap;


/**
 * 项目应用程序总控制
 */
class Application
{

    /**
     * @var Zend_Controller_Front
     */
    protected $_front;

    protected $_config;

    public function __construct($config)
    {
        $this->_front = Front::getInstance();
        $this->_config = $config;
    }

    /**
     * 在程序运行前，会初始化一系列配置和语句。
     * @return void
     */
    public function run()
    {
        $this->initPhpSettings();
        $this->_front->throwExceptions(true); // 设为 false 表示使用 zend framework 的 error handler
        $this->initErrorHandler();
        $this->initRoutes();
        $this->initView();
        $this->connectDatabase();
        // $this->startSession();

        $this->_front->dispatch();
    }

    /**
     * PHP环境相关设定
     * @return void
     */
    public function initPhpSettings()
    {
        $phpSettings = $this->_config['php_settings'];
        foreach ($phpSettings as $key => $value) {
            ini_set($key, $value);
        }
    }

    /**
     * 初始化错误处理方式
     * @return void
     */
    public function initErrorHandler()
    {
        if($this->_config['environment'] == 'production') {
            // production 环境下，只允许使用 Zend 来处理错误（显示友好的错误页面）
            $errorHandler = 'zend';
        } else {
            $errorHandler = strtolower($this->_config['error_handler']);
        }
        switch ($errorHandler) {
            case 'zend':
                $this->_front->throwExceptions(false);
                break;
            case 'whoops':
                // 只有在非 production 环境下，才允许使用 whoops
                if($this->_config['environment'] != 'production') {
                    $whoops = new Whoops;
                    $handler = new PrettyPageHandler;
                    $whoops->pushHandler($handler);
                    $whoops->register();
                }
                break;
            case 'php':
            default:
                break;
        }
    }

    /**
     * 初始化路由。路由表文件位于 application/configs/routes.php
     * @return void
     */
    public function initRoutes()
    {
        $this->_front->addModuleDirectory(PATH_APP . '/modules');
        $this->_front->setParam('useDefaultControllerAlways', false);
        $this->_front->setDefaultModule('site');
        // 去掉 zend framework 默认的路由设置。
        $router = $this->_front->getRouter();
        $router->removeDefaultRoutes();

        $routes = include_once PATH_APP . '/configs/routes.php';
        foreach($routes as $routeName => $route) {
            $router->addRoute($routeName, $route);
        }
    }


    /**
     * [initView description]
     * @return void
     */
    public function initView()
    {
        Zend_Layout::startMVC();
        $layout = Zend_Layout::getMvcInstance();
        $layout->setLayout('html5');
        $layout->disableLayout();
        $paths = $layout->getLayoutPath();
    }

    /**
     * 启动 session
     * @todo  To be finished ...
     * @return void
     */
    public function startSession()
    {
        $sessionConfig = $this->_config['session'];
        if(!empty($sessionConfig['save_handler'])) {
            $saveHanderOptions = $sessionConfig['save_handler_options'];
            $saveHander = new $sessionConfig['save_handler']($saveHanderOptions);
        } else {
            $saveHander = null;
        }
        unset($sessionConfig['save_handler'], $sessionConfig['save_handler_options']);
        Session::setOptions($sessionConfig);
        Session::setSaveHandler($saveHander);
        Session::start();
    }

    /**
     * Initilize the database connection.
     */
    public function connectDatabase($server = 'default')
    {
        $adapter = strtoupper($this->_config['database']['adapter']);
        $params = $this->_config['database']['params'];
        if(empty($adapter)) {
            throw new \Exception("Please specify a database adapter in your config file", 500);
        }
        switch ($adapter) {
            case 'MONGODB':
                /** @see http://cn2.php.net/manual/en/mongoclient.construct.php */
                $connection = new \MongoClient($params['connection_string'], array('connect' => false));
                \Mawelous\Yamop\Mapper::setDatabase($connection->{$params['dbname']});
                break;
            case 'PDO_MYSQL':
            default:
                // connect to mysql here...
                break;
        }
    }
}
