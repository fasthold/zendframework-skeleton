<?php

namespace Base\Controller\Response;

use Zend_Controller_Response_Http as HttpResponse;

class Http extends HttpResponse
{
    /**
     * Success response status code
     */
    const STATUS_SUCCESS = 1;

    /**
     * Failed response status code
     */
    const STATUS_FAILED = 0;
	/**
	 * 将当前的响应以 json 格式输出
	 *
	 * @return $this
	 */
	public function toJSON()
	{
		$this->setHeader('Content-type', 'application/json');
        return $this;
	}

    public function sendJSON($status = self::STATUS_SUCCESS, $message = null, $code = 200, $data = null)
    {
        $status = ($status == self::STATUS_SUCCESS || is_null($status)) ? self::STATUS_SUCCESS : self::STATUS_FAILED;
        $return = array('status' => $status, 'message' => $message);
        if(null !== $data) {
            $return['data'] = $data;
        }
        $this->setHttpResponseCode($code);
        $this->toJSON();
        $this->setBody(json_encode($return));

        return $this;
    }

    public function sendSuccessJSON($message = null, $data = null)
    {
        $this->sendJSON(null, $message, null, $data);
    }

    public function sendFailedJSON($message = null, $data = null, $code = 500)
    {
        $this->sendJSON(self::STATUS_FAILED, $message, $code, $data);
    }
}
