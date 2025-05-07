<?php

namespace App\Exceptions;

use Exception;

class CustomRedirectException extends Exception
{
    protected $extraData;

    public function __construct($message, $code = 0, $extraData = [], ?Exception $previous = null)
    {
        $this->extraData = $extraData;
        parent::__construct($message, $code, $previous);
    }

    public function getExtraData()
    {
        return $this->extraData;
    }
}
