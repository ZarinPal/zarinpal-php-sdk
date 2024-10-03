<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use JsonException;
use ZarinPal\Sdk\Endpoint\Fillable;
use ZarinPal\Sdk\Validator;

class VerifyRequest
{
    use Fillable;

    public ?string $merchantId = null;
    public int $amount;
    public string $authority;

    public function validate(): void
    {
        Validator::validateMerchantId($this->merchantId);
        Validator::validateAmount($this->amount);
        Validator::validateAuthority($this->authority);
    }

    /**
     * @throws JsonException
     */
    final public function toString(): string
    {
        $this->validate();

        return json_encode([
            "merchant_id" => $this->merchantId,
            "amount" => $this->amount,
            "authority" => $this->authority,
        ], JSON_THROW_ON_ERROR);
    }
}
