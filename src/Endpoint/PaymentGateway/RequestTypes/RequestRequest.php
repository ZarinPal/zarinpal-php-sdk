<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use InvalidArgumentException;
use ZarinPal\Sdk\Endpoint\Fillable;
use ZarinPal\Sdk\Validator;

class RequestRequest
{
    use Fillable;

    public ?string $merchantId = null;
    public int $amount;
    public string $description;
    public string $callback_url;
    public ?string $mobile = null;
    public ?string $email = null;
    public ?string $referrer_id = null;
    public ?string $currency = null;
    public ?array $wages = null;
    public ?string $cardPan = null;

    public function validate(): void
    {
        Validator::validateMerchantId($this->merchantId);
        Validator::validateAmount($this->amount);
        Validator::validateCallbackUrl($this->callback_url);
        Validator::validateMobile($this->mobile);
        Validator::validateEmail($this->email);
        Validator::validateCurrency($this->currency);
        Validator::validateWages($this->wages);
        Validator::validateCardPan($this->cardPan);
    }

    final public function toString(): string
    {
        $this->validate();

        $data = [
            "merchant_id" => $this->merchantId,
            "amount" => $this->amount,
            "callback_url" => $this->callback_url,
            "description" => $this->description,
            "metadata" => [
                "mobile" => $this->mobile,
                "email" => $this->email,
                "referrer_id" => $this->referrer_id,
            ]
        ];

        if ($this->currency) {
            $data['currency'] = $this->currency;
        }

        if ($this->wages) {
            $data['wages'] = $this->wages;
        }

        if ($this->cardPan) {
            $data['metadata']['card_pan'] = $this->cardPan;
        }

        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}
