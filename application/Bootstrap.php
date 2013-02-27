<?php

/**
 * Bootstrap class.
 *
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

	/**
	 * This init debug functions must be placed before other methods.
	 *
	 */
	protected function _initDebug() {
		require_once 'debug.php';
	}

	/**
	 * Init application configuration options
	 */
	protected function _initOptions() {
		$this->_options = $this->getOptions();
		Zend_Registry::set('app.options', $this->_options);
	}

	/**
	 * Initialize view
	 * 
	 * @return Zend_View
	 */
	protected function _initView() {
		$view = new Zend_View();
		$view->setEncoding($this->_options['resources']['view']['encoding']);
		$view->setBasePath($this->_options['resources']['view']['basePath']);
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);
		return $view;
	}

	/**
	 * Init autoloader
	 * @return Zend_Loader_Autoloader
	 */
	protected function _initAutoloader() {
		$autoloader = Zend_Loader_Autoloader::getInstance();
		// Register application base class.
		$autoloader->registerNamespace('Dipub_');
		$autoloader->setFallbackAutoloader(true);
		return $autoloader;
	}
	
	/**
	 * Init module loader
	 * @return Zend_Loader_Resourceloader
	 */
	protected function _initModuleAutoloader() {
		/**
		 * 加载默认模块
		 */
		$defaultModuleLoader = new Zend_Application_Module_Autoloader(
			array(
				'namespace' => '',
				'basePath' => APPLICATION_PATH ,
			)
		);

		/**
		 * 加载resource
		 */
		$resourceLoader = new Zend_Loader_Autoloader_Resource(
			array(
				'basePath'  => APPLICATION_PATH,
				'namespace' => '',
			)
		);

		$resourceLoader->addResourceType('acl', 'acls/', 'Acl')
			->addResourceType('form', 'forms/', 'Form')
			->addResourceType('model', 'models/','Model')
			->addResourceType('plugin', 'plugins','Plugin');
		return $resourceLoader;
	}
	
	/**
	 * Init session
	 *
	 * @return Zend_Session
	 */
	protected function _initSession() {
		
	}
	
  	/**
	 * init database
	 *
	 * @return Zend_Db_Adapter_Pdo_Mysql
	 */	
	protected function _initDB() {
        $resources = $this->getPluginResource('db');
        if(null !== $resources) {
			$db = $resources->getDbAdapter();
			Zend_Registry::set(REGKEY_DATABASE,$db);
			return $db;
        }
	}
}

