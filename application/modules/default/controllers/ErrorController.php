<?php

/**
 * Error handler class
 */
class ErrorController extends Zend_Controller_Action
{

	/**
	 * Error handler action
	 */
	public function errorAction()
	{
		$errors = $this->_getParam('error_handler');
		switch ($errors->type) {
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:

				// Error 404: route, controller or action not found
				$this->getResponse()->setHttpResponseCode(404);
				$this->view->message = 'Page not found';
				break;
			default:
				// application error
				$this->getResponse()->setHttpResponseCode(500);
				$this->view->message = 'Application error';
				break;
		}

		$clientIp = $this->getRequest()->getClientIp();
		if(APPLICATION_ENV != 'development' && $clientIp != '127.0.0.1') {
			echo $this->view->render('error/http-500.phtml');
			exit();
		}
		$this->view->exception = $errors->exception;
		$this->view->request   = $errors->request;
	}
}
