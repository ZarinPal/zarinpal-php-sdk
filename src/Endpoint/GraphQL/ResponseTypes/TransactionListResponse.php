<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL\ResponseTypes;

use ZarinPal\Sdk\Endpoint\Fillable;

class TransactionListResponse
{
    use Fillable;

    public string $id;
    public string $status;
    public int $amount;
    public string $description;
    public string $createdAt;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->status = $data['status'];
        $this->amount = $data['amount'];
        $this->description = $data['description'];
        $this->createdAt = $data['created_at'];
    }
}
