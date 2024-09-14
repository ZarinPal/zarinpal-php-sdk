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
                'list' => [
                    ['authority' => 'A000000000000000000000000000ydq5y838'],
                    ['authority' => 'A000000000000000000000000000ydq5y839'],
                ]
            ],
            'errors' => []
        ];

        $this->clientMock->expects($this->once())
            ->method('post')
            ->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($responseBody)));

        $unverified = new UnverifiedRequest();

        $response = $this->gateway->unverified($unverified);
        $this->assertCount(2, $response->list);
        $this->assertEquals('A000000000000000000000000000ydq5y838', $response->list[0]['authority']);
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
