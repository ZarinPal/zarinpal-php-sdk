<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes;

use ZarinPal\Sdk\Endpoint\Fillable;

class TransactionListRequest
{
    use Fillable;

    public string $terminalId;
    public ?string $filter = null; // Optional filter: PAID, VERIFIED, TRASH, ACTIVE, REFUNDED
    public ?string $id = null;
    public ?string $referenceId = null;
    public ?string $rrn = null;
    public ?string $cardPan = null;
    public ?string $email = null;
    public ?string $mobile = null;
    public ?string $description = null;
    public ?int $limit = 25;
    public ?int $offset = 0;

    public function validate(): void
    {
        if (empty($this->terminalId)) {
            throw new \InvalidArgumentException('Terminal ID is required.');
        }
    }

    public function toGraphQL(): string
    {
        $this->validate();

        return json_encode([
            'query' => '
                query Sessions(
                    $terminal_id: ID!,
                    $filter: FilterEnum,
                    $id: ID,
                    $reference_id: String,
                    $rrn: String,
                    $card_pan: String,
                    $email: String,
                    $mobile: CellNumber,
                    $description: String
                    $limit: Int
                    $offset: Int
                ) {
                    Session(
                        terminal_id: $terminal_id,
                        filter: $filter,
                        id: $id,
                        reference_id: $reference_id,
                        rrn: $rrn,
                        card_pan: $card_pan,
                        email: $email,
                        mobile: $mobile,
                        description: $description
                        limit: $limit
                        offset: $offset
                    ) {
                        id,
                        status,
                        amount,
                        description,
                        created_at
                    }
                }
            ',
            'variables' => [
                'terminal_id' => $this->terminalId,
                'filter' => $this->filter,
                'id' => $this->id,
                'reference_id' => $this->referenceId,
                'rrn' => $this->rrn,
                'card_pan' => $this->cardPan,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'description' => $this->description,
                'limit' => $this->limit,
                'offset' => $this->offset,
            ]
        ], JSON_THROW_ON_ERROR);
    }
}
