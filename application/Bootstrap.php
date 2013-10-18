<?php

/**
 * Bootstrap class.
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Init autoloader method must be placed before other methods.
     * @return Zend_Loader_Autoloader
     */
    protected function _initAutoloader()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true);
        return $autoloader;
    }

	/**
	 * This init debug
	 * @return void
	 */
	protected function _initDebug()
    {
		require_once 'debug.php';

        Zend_Controller_Front::getInstance()->throwExceptions(true);
        $run     = new Whoops\Run;
        $handler = new Whoops\Handler\PrettyPageHandler;
        $handler->setPageTitle("Oops! An Error Occurred");
        $run->pushHandler($handler);
        $run->register();

        $dumper = new \Ladybug\Dumper();
        Zend_Registry::set('dumper', $dumper);
	}

	/**
	 * Init application configuration options
	 */
	protected function _initOptions()
    {
		$this->_options = $this->getOptions();
		Zend_Registry::set('app.options', $this->_options);
	}

    /**
     * Init module loader
     * @return Zend_Loader_Autoloader_Resource
     */
    protected function _initModuleAutoloader()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->addModuleDirectory(PATH_APP . '/modules');
        // 去掉 zend framework 默认的路由设置。
        $router = $front->getRouter();
        $router->removeDefaultRoutes();
        d($router);
    }

    protected function _initRouter()
    {
        $front = Zend_Controller_Front::getInstance();
        // 去掉 zend framework 默认的路由设置。
        $router = $front->getRouter();
        $router->removeDefaultRoutes();

        $routes = include_once PATH_APP . '/configs/routes.php';
        foreach($routes as $routeName => $route) {
            $router->addRoute($routeName, $route);
        }
//        $front->addModuleDirectory(realpath(PATH_APP . '/modules/'));
        $front->addControllerDirectory(PATH_APP . '/modules/creation/controllers', 'creation');
    }

	/**
	 * Initialize view
	 *
	 * @return Zend_View
	 */
	protected function _initView()
    {
		$view = new Zend_View();
		$view->setEncoding($this->_options['resources']['view']['encoding']);
		$view->setBasePath($this->_options['resources']['view']['basePath']);
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);
		return $view;
	}

	/**
	 * Init session
	 *
	 * @return Zend_Session
	 */
	protected function _initSession()
    {

	}

  	/**
	 * init database
	 *
	 * @return Zend_Db_Adapter_Pdo_Mysql
	 */
	protected function _initDB()
    {
        $resources = $this->getPluginResource('db');
        if(null !== $resources) {
			$db = $resources->getDbAdapter();
			Zend_Registry::set(REGKEY_DATABASE,$db);
			return $db;
        }
	}
}

