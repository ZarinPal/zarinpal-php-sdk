<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use InvalidArgumentException;
use JsonException;
use ZarinPal\Sdk\Endpoint\Fillable;

class VerifyRequest
{
    use Fillable;

    public ?string $merchantId = null;
    public int $amount;
    public string $authority;

    public function validate(): void
    {
        $this->validateMerchantId();
        $this->validateAmount();
        $this->validateAuthority();
    }

    private function validateMerchantId(): void
    {
        if ($this->merchantId === null || !preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $this->merchantId)) {
            throw new InvalidArgumentException('Invalid merchant_id format. It should be a valid UUID.');
        }
    }

    private function validateAmount(): void
    {
        if ($this->amount <= 0) {
            throw new InvalidArgumentException('Amount must be greater than zero.');
        }
    }

    private function validateAuthority(): void
    {
        if (!preg_match('/^A[0-9a-zA-Z]{35}$/', $this->authority)) {
            throw new InvalidArgumentException('Invalid authority format. It should be a string starting with "A" followed by 32 alphanumeric characters.');
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
            "amount" => $this->amount,
            "authority" => $this->authority,
        ], JSON_THROW_ON_ERROR);
    }
}
