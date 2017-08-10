<?php

namespace apiSfs\core\Exceptions;

/**
 * Class ColiswebException
 *
 * Exception handling for Colisweb module
 *
 * @package apiSfs\core\Exceptions
 */
class ColiswebException extends \Exception
{
    public function __construct($message = null)
    {
        if (null === $message) {
            $message = 'Error using Colisweb module';
        } else {
            $message = 'Error using Colisweb module: '.$message;
        }

        parent::__construct($message);
    }
}