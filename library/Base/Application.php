<?php

namespace Base;

use Zend_Controller_Front as Front;
use Zend_Controller_Router_Route as Route;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;


class Application
{

    /**
     * @var Zend_Controller_Front
     */
    protected $_front;

    public function __construct()
    {
        $this->_front = Front::getInstance();
    }

    public function run()
    {
//        $this->_front->throwExceptions(true);

        $this->initRoutes();
        $this->initDebug();

        $this->_front->dispatch();
    }

    public function initRoutes()
    {
        $this->_front->addModuleDirectory(PATH_APP . '/modules');
        $this->_front->setDefaultModule('default');
        // 去掉 zend framework 默认的路由设置。
        $router = $this->_front->getRouter();
        $router->removeDefaultRoutes();

        $routes = include_once PATH_APP . '/configs/routes.php';
        foreach($routes as $routeName => $route) {
            $router->addRoute($routeName, $route);
        }
    }

    public function initDebug()
    {
        $run     = new Run;
        $handler = new PrettyPageHandler;
        $handler->setPageTitle("Woops!");
        $run->pushHandler($handler);
        $run->register();
    }
}
