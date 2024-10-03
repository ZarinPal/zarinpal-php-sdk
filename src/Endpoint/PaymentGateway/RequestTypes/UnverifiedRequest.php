<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use JsonException;
use ZarinPal\Sdk\Endpoint\Fillable;
use ZarinPal\Sdk\Validator;

class UnverifiedRequest
{
    use Fillable;

    public ?string $merchantId = null;

    public function validate(): void
    {
        Validator::validateMerchantId($this->merchantId);
    }

    /**
     * @throws JsonException
     */
    final public function toString(): string
    {
        $this->validate();

        return json_encode([
            "merchant_id" => $this->merchantId,
        ], JSON_THROW_ON_ERROR);
    }
}
