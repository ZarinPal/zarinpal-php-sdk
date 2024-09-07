<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes;

use ZarinPal\Sdk\Endpoint\Fillable;

class RefundRequest
{
    use Fillable;

    public const METHOD_PAYA = 'PAYA';
    public const METHOD_CARD = 'CARD';

    public const REASON_CUSTOMER_REQUEST = 'CUSTOMER_REQUEST';
    public const REASON_DUPLICATE_TRANSACTION = 'DUPLICATE_TRANSACTION';
    public const REASON_SUSPICIOUS_TRANSACTION = 'SUSPICIOUS_TRANSACTION';
    public const REASON_OTHER = 'OTHER';

    public string $sessionId;
    public int $amount;
    public string $description;
    public string $method = self::METHOD_PAYA; // Default to PAYA for normal refund
    public string $reason = self::REASON_CUSTOMER_REQUEST; // Default reason

    public function validate(): void
    {
        if (empty($this->sessionId)) {
            throw new \InvalidArgumentException('Session ID is required.');
        }
        if ($this->amount < 20000) {
            throw new \InvalidArgumentException('Amount must be at least 20000 IRR.');
        }
    }

    public function toGraphQL(): string
    {
        $this->validate();

        return json_encode([
            'query' => '
                mutation AddRefund($session_id: ID!, $amount: BigInteger!, $description: String, $method: InstantPayoutActionTypeEnum, $reason: RefundReasonEnum) {
                    resource: AddRefund(
                        session_id: $session_id,
                        amount: $amount,
                        description: $description,
                        method: $method,
                        reason: $reason
                    ) {
                        terminal_id,
                        id,
                        amount,
                        timeline {
                            refund_amount,
                            refund_time,
                            refund_status
                        }
                    }
                }
            ',
            'variables' => [
                'session_id' => $this->sessionId,
                'amount' => $this->amount,
                'description' => $this->description,
                'method' => $this->method,
                'reason' => $this->reason,
            ]
        ], JSON_THROW_ON_ERROR);
    }
}
