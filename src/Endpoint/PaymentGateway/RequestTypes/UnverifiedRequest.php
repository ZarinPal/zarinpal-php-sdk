<?php


namespace ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes;

use JsonException;
use ZarinPal\Sdk\Endpoint\Fillable;

class UnverifiedRequest
{
    use Fillable;

    public ?string $merchantId = null;
    public int $amount;
    public string $authority;


    /**
     * @throws JsonException
     */
    final public function toString(): string
    {
        return json_encode([
            "merchant_id" => $this->merchantId,
        ], JSON_THROW_ON_ERROR);
    }

}