<?php

/**
 * 应用程序所有 route 配置
 *
 * @example: 'user_register' => new Route('/user/:username', array('controller' => 'user', 'action' => 'info')),
 * @link http://framework.zend.com/manual/1.12/en/zend.controller.router.html
 */

use Zend_Controller_Router_Route as Route;

return array(
    'index' => new Route('/', array('module' => 'site','controller' => 'index', 'action' => 'index')),
);
