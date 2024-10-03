<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use ZarinPal\Sdk\Endpoint\Fillable;
use ZarinPal\Sdk\Validator;

class ReverseRequest
{
    use Fillable;

    public ?string $merchantId = null;
    public string $authority;

    public function validate(): void
    {
        Validator::validateMerchantId($this->merchantId);
        Validator::validateAuthority($this->authority);
    }

    final public function toString(): string
    {
        $this->validate();

        return json_encode([
            "merchant_id" => $this->merchantId,
            "authority" => $this->authority,
        ], JSON_THROW_ON_ERROR);
    }
}
