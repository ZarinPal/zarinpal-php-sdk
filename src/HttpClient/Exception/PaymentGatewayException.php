<?php

namespace ZarinPal\Sdk\HttpClient\Exception;

class PaymentGatewayException extends ResponseException
{
    private ?array $validationErrors;

    public function __construct($errors)
    {
        $this->validationErrors = $errors['validations'];
        parent::__construct($errors['message'], $errors['code'], null);
    }


    final public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}