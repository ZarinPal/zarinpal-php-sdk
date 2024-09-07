<?php

namespace Tests\Graphql;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\BaseTestCase;
use ZarinPal\Sdk\Endpoint\GraphQL\TransactionService;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\TransactionListRequest;

class TransactionServiceTest extends BaseTestCase
{
    private $transactionService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the response without the errors key
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'data' => [
                    'Session' => [
                        [
                            'id' => '1234567890',
                            'status' => 'PAID',
                            'amount' => 10000,
                            'description' => 'Test transaction',
                            'created_at' => '2024-08-25T15:00:00+03:30'
                        ]
                    ]
                ]
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $mockClient = new Client(['handler' => $handlerStack]);

        // Inject mock client into the TransactionService
        $this->transactionService = new TransactionService($this->getOptions());
        $reflection = new \ReflectionClass($this->transactionService);
        $clientProperty = $reflection->getProperty('client');
        $clientProperty->setAccessible(true);
        $clientProperty->setValue($this->transactionService, $mockClient);
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
