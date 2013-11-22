<?php

namespace Base\Db\Exception;

use Exception;

/**
 * 找不到模型数据时的 exception
 */
class ModelNotFoundException extends Exception
{
    public function __construct($message = 'Not Found', $code = 404)
    {
        $this->code = $code;
        $this->message = $message;
    }
}
