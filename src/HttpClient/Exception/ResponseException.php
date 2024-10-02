<?php

namespace ZarinPal\Sdk\HttpClient\Exception;


use Exception;

class ResponseException extends \Exception
{
    private $errorDetails;

    public function __construct($message, $code, $previous = null, array $errorDetails = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errorDetails = $errorDetails;
    }

    public function getErrorDetails()
    {
        return $this->errorDetails;
    }
}