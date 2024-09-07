<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use InvalidArgumentException;
use ZarinPal\Sdk\Endpoint\Fillable;

class RequestRequest
{
    use Fillable;

    public ?string $merchantId = null;
    public int $amount;
    public string $description;
    public string $callback_url;
    public ?string $mobile = null;
    public ?string $email = null;
    public ?string $currency = null;
    public ?array $wages = null;
    public ?string $cardPan = null;

    public function validate(): void
    {
        $this->validateMerchantId();
        $this->validateAmount();
        $this->validateCallbackUrl();
        $this->validateMobile();
        $this->validateEmail();
        $this->validateCurrency();
        $this->validateWages();
        $this->validateCardPan();
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

    private function validateCallbackUrl(): void
    {
        if (!preg_match('/^https?:\/\/.*/', $this->callback_url)) {
            throw new InvalidArgumentException('Invalid callback URL format. It should start with http:// or https://.');
        }
    }

    private function validateMobile(): void
    {
        if ($this->mobile !== null && !preg_match('/^09[0-9]{9}$/', $this->mobile)) {
            throw new InvalidArgumentException('Invalid mobile number format.');
        }
    }

    private function validateEmail(): void
    {
        if ($this->email !== null && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format.');
        }
    }

    private function validateCurrency(): void
    {
        $validCurrencies = ['IRR', 'IRT'];
        if ($this->currency !== null && !in_array($this->currency, $validCurrencies)) {
            throw new InvalidArgumentException('Invalid currency format. Allowed values are "IRR" or "IRT".');
        }
    }

    private function validateWages(): void
    {
        if ($this->wages !== null) {
            foreach ($this->wages as $wage) {
                if (!isset($wage['iban']) || !preg_match('/^IR[0-9]{2}[0-9A-Z]{1,24}$/', $wage['iban'])) {
                    throw new InvalidArgumentException('Invalid IBAN format in wages.');
                }
                if (!isset($wage['amount']) || $wage['amount'] <= 0) {
                    throw new InvalidArgumentException('Wage amount must be greater than zero.');
                }
                if (!isset($wage['description']) || strlen($wage['description']) > 255) {
                    throw new InvalidArgumentException('Wage description must be provided and less than 255 characters.');
                }
            }
        }
    }

    private function validateCardPan(): void
    {
        if ($this->cardPan !== null && !preg_match('/^[0-9]{16}$/', $this->cardPan)) {
            throw new InvalidArgumentException('Invalid card PAN format. It should be a 16-digit number.');
        }
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
