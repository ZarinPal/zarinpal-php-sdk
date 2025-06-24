<?php

namespace ZarinPal\Sdk\Endpoint\PaymentGateway\ResponseTypes;

use ZarinPal\Sdk\Endpoint\Fillable;

class FeeCalculationResponse
{
    use Fillable;

    public int $amount;
    public int $fee;
    public string $fee_type;
    public int $suggested_amount;
    public int $code;
    public string $message;
} 