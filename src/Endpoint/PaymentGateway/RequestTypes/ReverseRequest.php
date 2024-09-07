<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use InvalidArgumentException;
use ZarinPal\Sdk\Endpoint\Fillable;
use ZarinPal\Sdk\Options;

class ReverseRequest
{
    use Fillable;

    public string $merchantId;
    public string $authority;

    public function __construct(Options $options)
    {
        $this->merchantId = $options->getMerchantId();
    }

    public function validate(): void
    {
        $this->validateMerchantId();
        $this->validateAuthority();
    }

    private function validateMerchantId(): void
    {
        if ($this->merchantId === null || !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $this->merchantId)) {
            throw new InvalidArgumentException('Invalid merchant_id format. It should be a valid UUID.');
        }
    }

    private function validateAuthority(): void
    {
        if ($this->authority === null || !preg_match('/^A[0-9a-zA-Z]{32}$/', $this->authority)) {
            throw new InvalidArgumentException('Invalid authority format. It should be a string starting with "A" followed by 32 alphanumeric characters.');
        }
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
