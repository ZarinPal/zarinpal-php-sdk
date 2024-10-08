<?php

namespace Tests\Graphql;

use Tests\BaseTestCase;
use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Endpoint\GraphQL\TransactionService;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\TransactionListRequest;
use ZarinPal\Sdk\Endpoint\GraphQL\ResponseTypes\TransactionListResponse;
use ZarinPal\Sdk\Options;

class TransactionServiceTest extends BaseTestCase
{
    private $transactionService;

    protected function setUp(): void
    {
        parent::setUp();

        $clientBuilder = new ClientBuilder();
        $options = new Options([
            'client_builder' => $clientBuilder,
            'access_token' => 'your_access_token',
            'graphql_url' => 'https://your-graphql-endpoint',
        ]);

        $this->transactionService = $this->createMock(TransactionService::class);
        $this->transactionService->method('getTransactions')->willReturn([
            new TransactionListResponse([
                'id' => '1234567890',
                'status' => 'PAID',
                'amount' => 10000,
                'description' => 'Test transaction',
                'created_at' => '2024-08-25T15:00:00+03:30'
            ])
        ]);
    }

    public function testGetTransactions()
    {
        $transactionRequest = new TransactionListRequest();
        $transactionRequest->terminalId = '238';

        $transactions = $this->transactionService->getTransactions($transactionRequest);

        $this->assertCount(1, $transactions);
        $this->assertEquals('1234567890', $transactions[0]->id);
        $this->assertEquals('PAID', $transactions[0]->status);
        $this->assertEquals(10000, $transactions[0]->amount);
        $this->assertEquals('Test transaction', $transactions[0]->description);
    }
}
