<?php

declare(strict_types=1);

namespace PrisonFellowship\NomadPHPSDK\Exceptions;

use Exception;

class NomadMediaException extends Exception
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        // Calls the parent class (Exception) constructor
        parent::__construct($message, $code, $previous);
    }

    /**
     * Custom string representation of object
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__.": [{$this->code}]: {$this->message}\n";
    }
}
