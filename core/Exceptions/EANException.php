<?php

namespace apiSfs\core\Exceptions;

/**
 * Class EANException
 *
 * Exception handling for EAN module
 *
 * @package apiSfs\core\Exceptions
 */
class EANException extends \Exception
{
    public function __construct($message = null)
    {
        if (null === $message) {
            $message = 'Error using EAN';
        } else {
            $message = 'Error using EAN: '.$message;
        }

        parent::__construct($message);
    }
}