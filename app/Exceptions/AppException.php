<?php

namespace App\Exceptions;

use Exception;

class AppException extends Exception
{
    /**
     * AppException constructor.
     *
     * Keeping the signature similar to your original Symfony class.
     *
     * @param string         $message
     * @param int            $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * String representation of the exception.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
