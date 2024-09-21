<?php

namespace Tests\PaymentGateway;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Mock\Client as MockClient;
use Tests\BaseTestCase;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\PaymentGateway;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\RequestRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\VerifyRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\UnverifiedRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\ReverseRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\InquiryRequest;

class PaymentGatewayTest extends BaseTestCase
{
    private $gateway;
    private $clientMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->clientMock = $this->createMock(HttpMethodsClientInterface::class);

        $zarinpal = new ZarinPal($this->getOptions());
        $zarinpal->setHttpClient($this->clientMock);

        $this->gateway = new PaymentGateway($zarinpal);
    }

    public function testRequest()
    {
        $responseBody = [
            'data' => [
                'authority' => 'A00000000000000000000000000123456',
            ],
            'errors' => []
        ];

        $this->clientMock->expects($this->once())
            ->method('post')
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($responseBody)));

        $request = new RequestRequest();
        $request->amount = 10000;
        $request->description = 'Test Payment';
        $request->callback_url = 'https://callback.url';
        $request->mobile = '09370000000';
        $request->email = 'test@example.com';

        $response = $this->gateway->request($request);
        $this->assertEquals('A00000000000000000000000000123456', $response->authority);
    }

    public function testVerify()
    {
        $responseBody = [
            'data' => [
                'code' => 100,
                'ref_id' => '1234567890',
            ],
            'errors' => []
        ];

        $this->clientMock->expects($this->once())
            ->method('post')
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($responseBody)));

        $verify = new VerifyRequest();
        $verify->amount = 15000;
        $verify->authority = 'A000000000000000000000000000ydq5y838';

        $response = $this->gateway->verify($verify);
        $this->assertEquals(100, $response->code);
    }

    public function testUnverified()
    {
        $responseBody = [
            'data' => [
                'code' => 100,
                'message' => 'Success',
                'authorities' => [
                    [
                        'authority' => 'A000000000000000000000000000ydq5y838',
                        'amount' => 50000,
                        'callback_url' => 'https://example.com/callback',
                        'referer' => 'https://example.com/referer',
                        'date' => '2024-09-22 10:00:00'
                    ],
                    [
                        'authority' => 'A000000000000000000000000000ydq5y839',
                        'amount' => 75000,
                        'callback_url' => 'https://example.com/callback2',
                        'referer' => 'https://example.com/referer2',
                        'date' => '2024-09-22 12:00:00'
                    ],
                ],
            ],
            'errors' => []
        ];

        $this->clientMock->expects($this->once())
            ->method('post')
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($responseBody)));

        $unverified = new UnverifiedRequest();
        $response = $this->gateway->unverified($unverified);

        $this->assertEquals(100, $response->code);
        $this->assertCount(2, $response->authorities);
        $this->assertEquals('A000000000000000000000000000ydq5y838', $response->authorities[0]['authority']);
        $this->assertEquals(50000, $response->authorities[0]['amount']);
        $this->assertEquals('https://example.com/callback', $response->authorities[0]['callback_url']);
        $this->assertEquals('2024-09-22 10:00:00', $response->authorities[0]['date']);
        $this->assertEquals('A000000000000000000000000000ydq5y839', $response->authorities[1]['authority']);
        $this->assertEquals(75000, $response->authorities[1]['amount']);
        $this->assertEquals('https://example.com/callback2', $response->authorities[1]['callback_url']);
        $this->assertEquals('2024-09-22 12:00:00', $response->authorities[1]['date']);
    }

    public function testReverse()
    {
        $responseBody = [
            'data' => [
                'status' => 'Success',
            ],
            'errors' => []
        ];

        $this->clientMock->expects($this->once())
            ->method('post')
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($responseBody)));

        $reverseRequest = new ReverseRequest();
        $reverseRequest->authority = 'A000000000000000000000000000ydq5y838';

        $response = $this->gateway->reverse($reverseRequest);
        $this->assertEquals('Success', $response->status);
    }

    public function testInquiry()
    {
        $responseBody = [
            'data' => [
                'amount' => 15000,
            ],
            'errors' => []
        ];

        $this->clientMock->expects($this->once())
            ->method('post')
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($responseBody)));

        $inquiryRequest = new InquiryRequest();
        $inquiryRequest->authority = 'A000000000000000000000000000ydq5y838';

        $response = $this->gateway->inquiry($inquiryRequest);
        $this->assertEquals(15000, $response->amount);
    }
}
