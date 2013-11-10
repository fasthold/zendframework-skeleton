<?php

namespace Base\Db\Exception;

use Exception;

class ModelNotFoundException extends Exception
{
    public function __construct($message = 'Not Found', $code = 404)
    {
        $this->code = $code;
        $this->message = $message;
    }
}
