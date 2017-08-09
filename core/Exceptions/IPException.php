<?php

namespace apiSfs\core\Exceptions;

class IPException extends \Exception
{
    public function __construct($message = null)
    {
        if (null === $message) {
            $message = 'Error using IP module';
        } else {
            $message = 'Error using IP module: '.$message;
        }

        parent::__construct($message);
    }
}
