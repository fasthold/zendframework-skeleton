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
use Base\Controller\Response\Http as HttpResponse;
use Base\Controller\Request\Http as HttpRequest;


/**
 * 项目应用程序总控制
 */
class Application
{

    /**
     * @var \Zend_Controller_Front
     */
    protected $_front;

    /**
     *
     * @var \Base\Controller\Request\Http
     */
    protected $_request;

    /**
     *
     * @var \Base\Controller\Response\Http
     */
    protected $_response;

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
        $this->initErrorHandler();
        $this->initView();
        $this->initRequest(); // 必须放在 initRoutes() 的前面
        $this->initResponse();
        $this->initRoutes();
        $this->connectDatabase();
        // $this->startSession();

        $this->_front->dispatch($this->_request, $this->_response);
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
                $this->_front->throwExceptions(false); // 设为 false 表示使用 zend framework 的 error handler
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
                $this->_front->throwExceptions(true);
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

        $routes = include PATH_APP . '/configs/routes.php';
        foreach($routes as $routeName => $route) {
            // 增加对 request method 的判定。如果 method 不符合，则该条 route 直接就不被加入到 router 里
            $options = $route->getDefaults();
            $method = isset($options['method']) ? strtoupper($options['method']) : 'GET';
            if($method === $this->_request->getMethod()) {
                $router->addRoute($routeName, $route);                
            }
        }
    }


    /**
     * 初始化 View
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
     * 增加扩展 Request 对象
     */
    public function initRequest()
    {
        $this->_request = new HttpRequest;
    }

    /**
     * 增加扩展 Response 对象
     */
    public function initResponse()
    {
        $this->_response = new HttpResponse;
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
                $connection = new \MongoClient($params['connection_string'], $params['options']);
                \Mawelous\Yamop\Mapper::setDatabase($connection->{$params['dbname']});
                break;
            case 'PDO_MYSQL':
            default:
                // connect to mysql here...
                break;
        }
    }
}
