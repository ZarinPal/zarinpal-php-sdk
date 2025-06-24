<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use ZarinPal\Sdk\Endpoint\Fillable;
use ZarinPal\Sdk\Validator;

class FeeCalculationRequest
{
    use Fillable;

    public ?string $merchantId = null;
    public int $amount;
    public ?string $currency = null;

    public function validate(): void
    {
        Validator::validateMerchantId($this->merchantId);
        Validator::validateAmount($this->amount);
        Validator::validateCurrency($this->currency);
    }

    final public function toString(): string
    {
        $this->validate();

        $data = [
            "merchant_id" => $this->merchantId,
            "amount" => $this->amount,
        ];

        if ($this->currency !== null) {
            $data['currency'] = $this->currency;
        }

        return json_encode($data, JSON_THROW_ON_ERROR);
    }
} 