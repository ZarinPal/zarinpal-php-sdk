<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes;

use ZarinPal\Sdk\Endpoint\Fillable;
use ZarinPal\Sdk\Validator;

class TransactionListRequest
{
    use Fillable;

    public string $terminalId;
    public ?string $filter = null;
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
        Validator::validateTerminalId($this->terminalId);
        Validator::validateFilter($this->filter);
        Validator::validateEmail($this->email);
        Validator::validateMobile($this->mobile);
        Validator::validateCardPan($this->cardPan);
        Validator::validateLimit($this->limit);
        Validator::validateOffset($this->offset);
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
                    $description: String,
                    $limit: Int,
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
                        description: $description,
                        limit: $limit,
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
