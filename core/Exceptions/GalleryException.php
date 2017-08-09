<?php

namespace apiSfs\core\Exceptions;

class GalleryException extends \Exception
{
    public function __construct($message = null)
    {
        if (null === $message) {
            $message = 'Error using gallery data';
        } else {
            $message = 'Error using gallery data: '.$message;
        }

        parent::__construct($message);
    }
}