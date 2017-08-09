<?php

namespace apiSfs\core\Exceptions;

class MaxmindException extends \Exception
{
    public function __construct($message = null)
    {
        if (null === $message) {
            $message = 'Error using Maxmind module';
        } else {
            $message = 'Error using Maxmind module: '.$message;
        }

        parent::__construct($message);
    }
}