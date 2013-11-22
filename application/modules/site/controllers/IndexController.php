<?php

/**
 * This file is part of ZendFramework skeleton.
 */

use Base\Controller\Action as BaseController;
use Zend_Session as Session;
use site\models\User;

/**
 * Site Index Controller
 */
class IndexController extends BaseController
{
    public function indexAction()
    {
        // Your logical code goes here...
        $this->_loadLayout();
    }

    protected function _loadLayout()
    {
        $layout = $this->_helper->layout->getLayoutInstance();
        $layout->setLayoutPath(__DIR__ .'/../views/layouts');
        $layout->setLayout('html5');
    }
}
