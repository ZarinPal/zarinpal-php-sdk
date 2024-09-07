<?php

namespace Tests\Graphql;

use Tests\BaseTestCase;
use ZarinPal\Sdk\Endpoint\GraphQL\RefundService;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\RefundRequest;

class GraphQLRefundTest extends BaseTestCase
{
    private $refundService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refundService = $this->createMock(RefundService::class);
    }

    public function testRefund()
    {
        $refundRequest = new RefundRequest();
        $refundRequest->sessionId = '385404539';
        $refundRequest->amount = 20000;
        $refundRequest->description = 'Test Refund';

        $mockResponse = [
            'id' => '1234567890',
            'terminal_id' => '238',
            'amount' => 20000,
            'timeline' => [
                'refund_amount' => 20000,
                'refund_time' => '2024-08-25T15:00:00+03:30',
                'refund_status' => 'PENDING'
            ]
        ];

        $this->refundService->method('refund')->willReturn($mockResponse);

        $response = $this->refundService->refund($refundRequest);

        $this->assertArrayHasKey('id', $response);
        $this->assertEquals('1234567890', $response['id']);
    }
}
