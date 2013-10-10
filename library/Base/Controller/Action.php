<?php

/**
 * This file is part of ZendFramework skeleton.
 */

/**
 * Controller 基类
 *
 */
class Base_Controller_Action extends Zend_Controller_Action {

	/**
	 * 包含若干 Zend_Session 的数组
	 *
	 * @var array
	 */
	protected $_session;

    /**
     * 配置项
     * @var array
     */
    protected $_options = null;

	/**
	 *
	 * @var Zend_Controller_Action_Helper_FlashMessenger
	 */
	protected $_flashMessenger;

    /**
     * 禁用 view
     */
    protected function noRender()
    {
		$this->_helper->viewRenderer->setNoRender();
	}

    /**
     * 禁用 layout
     */
    protected function noLayout()
    {
		$this->_helper->layout->disableLayout();
	}

	/**
	 * 设置布局
	 * @param string $layout
	 */
	protected function setLayout($layout)
    {
		$this->_helper->layout->setLayout($layout);
		return $this;
	}
	
	/**
	 * 关闭调试信息
	 */
	protected function noDebug()
    {
		Zend_Registry::set('DEBUG',-1);
	}

	/**
	 * 开启调试信息
	 */
	protected function enableDebug($level=1) {
		Zend_Registry::set('DEBUG',$level);
	}

	/**
	 * 从配置文件中取出所有配置选项
	 *
	 * @return Array
	 */
	public function getAppOptions()
    {
		$args = $this->getInvokeArgs();
		$this->_options = $args['bootstrap']->getOptions();
		return $this->_options;
	}

	/**
	 * 取单个配置选项
	 *
	 * @param string $key
	 * @return Mix
	 */
	public function getAppOption($key)
    {
		if(null === $this->_options) {
			$this->getAppOptions();
		}
		return (isset($this->_options[$key])) ? $this->_options[$key] : null;
	}
}