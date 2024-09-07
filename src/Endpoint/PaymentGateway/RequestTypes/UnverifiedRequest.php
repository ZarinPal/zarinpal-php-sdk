<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use InvalidArgumentException;
use JsonException;
use ZarinPal\Sdk\Endpoint\Fillable;

class UnverifiedRequest
{
    use Fillable;

    public ?string $merchantId = null;

    public function validate(): void
    {
        $this->validateMerchantId();
    }

    private function validateMerchantId(): void
    {
        if ($this->merchantId === null || !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $this->merchantId)) {
            throw new InvalidArgumentException('Invalid merchant_id format. It should be a valid UUID.');
        }
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
