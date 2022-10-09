<?php


namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

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

    final public function toString(): string
    {
        return json_encode([
            "merchant_id" => $this->merchantId,
            "amount" => $this->amount,
            "callback_url" => $this->callback_url,
            "description" => $this->description,
            "metadata" => [
                "mobile" => $this->mobile,
                "email" => $this->email,
            ]
        ], JSON_THROW_ON_ERROR);
    }

}