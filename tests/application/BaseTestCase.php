<?php

define('PATH_ROOT', realpath(__DIR__.'/../../'));
define('PATH_APP', PATH_ROOT.'/application');
define('APPLICATION_ENV', 'test');

class BaseTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        parent::setUp(); // 必须放在其他代码之前
        $this->initRoute();
    }

    /**
     * 加载路由
     */
    public function initRoute()
    {
        $frontController = $this->getFrontController();
        $frontController->addModuleDirectory(PATH_APP . '/modules');
        $frontController->setParam('useDefaultControllerAlways', false);
        $frontController->setDefaultModule('site');
        // 去掉 zend framework 默认的路由设置。
        $router = $frontController->getRouter();
        $router->removeDefaultRoutes();

        $routes = include PATH_APP . '/configs/routes.php';
        foreach($routes as $routeName => $route) {
            $router->addRoute($routeName, $route);
        }
    }

    /**
     * 返回响应中不包含任何php的错误
     *
     * @param  string $text response text
     *
     * @return void
     */
    public function assertNoSystemError($text)
    {
        $this->assertNotContains('Strict Standards:', $text);
        $this->assertNotContains('Fatal Error:', $text);
        $this->assertNotContains('Notice:', $text);
        $this->assertNotContains('Deprecated:', $text);
    }
}
