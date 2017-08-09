<?php

namespace apiSfs\core\Exceptions;

class IPException extends \Exception
{
    public function __construct($message = null)
    {
        if (null === $message) {
            $message = 'Invalid IP address given';
        } else {
            $message = 'Invalid IP address given: '.$message;
        }

        parent::__construct($message);
    }
}
