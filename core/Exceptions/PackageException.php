<?php

namespace apiSfs\core\Exceptions;

class PackageException extends \Exception
{
    public function __construct($message = null)
    {
        if (null === $message) {
            $message = 'Error processing package handling';
        } else {
            $message = 'Error processing package handling: '.$message;
        }
        
        parent::__construct($message);
    }
}