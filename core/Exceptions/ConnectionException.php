<?php

namespace apiSfs\core\exceptions;

class ConnectionException extends \Exception
{
    public function __construct()
    {
        $message = 'Failed to connect to database';
        parent::__construct($message);
    }
}