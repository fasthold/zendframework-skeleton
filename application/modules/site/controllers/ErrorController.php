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
        $errorCode = $errors->exception->getCode();

        switch ($errors->type) {
            // Error 404: route, controller or action not found
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
                $this->sendErrorResponse(404, "Page not found");
                break;

            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                $this->sendErrorResponse($errorCode, $errors->exception->getMessage());
                break;
            default:
                // application error
                $this->sendErrorResponse(500, "Server error");
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

    /**
     * 发送错误响应
     * @param  integer $code    http status code
     * @param  string  $message error message
     * @return void
     */
    public function sendErrorResponse($code = 500, $message = 'Application error')
    {
        if(!is_int($code) || strlen($code) != 3) {
            $code = 500;
            $message = 'Application error';
        }
        $this->view->message = $message;
        $this->getResponse()->setHttpResponseCode($code);
    }
}
