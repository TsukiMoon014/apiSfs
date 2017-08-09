<?php

namespace apiSfs\core\Exceptions;

/**
 * Class IPException
 *
 * Exception handling for IP tracking module
 *
 * @package apiSfs\core\Exceptions
 */
class IPException extends \Exception
{
    public function __construct($message = null)
    {
        if (null === $message) {
            $message = 'Error using IP tracking module';
        } else {
            $message = 'Error using IP tracking module: '.$message;
        }

        parent::__construct($message);
    }
}
