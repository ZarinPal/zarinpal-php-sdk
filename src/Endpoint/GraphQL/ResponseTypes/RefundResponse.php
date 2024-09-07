<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL\ResponseTypes;

use ZarinPal\Sdk\Endpoint\Fillable;

class RefundResponse
{
    use Fillable;

    public string $terminalId;
    public string $id;
    public int $amount;
    public array $timeline;

    public function __construct(array $data)
    {
        $this->terminalId = $data['terminal_id'];
        $this->id = $data['id'];
        $this->amount = $data['amount'];
        $this->timeline = $data['timeline'];
    }
}
