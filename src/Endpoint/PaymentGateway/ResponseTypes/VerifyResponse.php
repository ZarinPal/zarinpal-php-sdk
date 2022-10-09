<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\ResponseTypes;

use ZarinPal\Sdk\Endpoint\Fillable;

class VerifyResponse
{

    use Fillable;

    public string $authority;
    public int $code;
    public string $message;
    public string $ref_id;
    public string $card_pan;
    public string $card_hash;
    public string $fee_type;
    public string $fee;

}