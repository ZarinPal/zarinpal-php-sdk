<?php

declare(strict_types=1);

namespace ZarinPal\Sdk\Endpoint\PaymentGateway;

use Exception;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use ZarinPal\Sdk\Endpoint\PaymentGateway\ResponseTypes\RequestResponse;
use ZarinPal\Sdk\Endpoint\PaymentGateway\ResponseTypes\UnverifiedResponse;
use ZarinPal\Sdk\Endpoint\PaymentGateway\ResponseTypes\VerifyResponse;
use ZarinPal\Sdk\HttpClient\Exception\PaymentGatewayException;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\ZarinPal;

final class PaymentGateway
{
    private const BASE_URL = '/pg/v4/payment/';
    private const START_PAY = '/pg/StartPay/';
    private const REQUEST_URI = self::BASE_URL . 'request.json';
    private const VERIFY_URI = self::BASE_URL . 'verify.json';
    private const UNVERIFIED_URI = self::BASE_URL . 'unVerified.json';
    private const REVERSE_URI = self::BASE_URL . 'reverse.json';
    private const INQUIRY_URI = self::BASE_URL . 'inquiry.json';

    private ZarinPal $sdk;

    public function __construct(ZarinPal $sdk)
    {
        $this->sdk = $sdk;
    }

    public function request(RequestTypes\RequestRequest $request): RequestResponse
    {
        $this->fillMerchantId($request);
        $response = $this->httpHandler(self::REQUEST_URI, $request->toString());

        return new RequestResponse($response['data']);
    }

    public function getRedirectUrl(string $authority): string
    {
        $baseUrl = (string) $this->sdk->getOptions()->getBaseUrl();
        return rtrim($baseUrl, '/') . self::START_PAY . $authority;
    }

    public function verify(RequestTypes\VerifyRequest $request): VerifyResponse
    {
        $this->fillMerchantId($request);
        $response = $this->httpHandler(self::VERIFY_URI, $request->toString());

        return new VerifyResponse($response['data']);
    }

    public function unverified(RequestTypes\UnverifiedRequest $request): UnverifiedResponse
    {
        $this->fillMerchantId($request);
        $response = $this->httpHandler(self::UNVERIFIED_URI, $request->toString());

        return new UnverifiedResponse($response['data']);
    }

    public function reverse(RequestTypes\ReverseRequest $request): RequestResponse
    {
        $this->fillMerchantId($request);
        $response = $this->httpHandler(self::REVERSE_URI, $request->toString());

        return new RequestResponse($response['data']);
    }

    public function inquiry(RequestTypes\InquiryRequest $request): RequestResponse
    {
        $this->fillMerchantId($request);
        $response = $this->httpHandler(self::INQUIRY_URI, $request->toString());

        return new RequestResponse($response['data']);
    }

    private function fillMerchantId($request): void
    {
        if ($request->merchantId === null) {
            $request->merchantId = $this->sdk->getMerchantId();
        }
    }

    private function httpHandler(string $uri, string $body): array
    {
        try {
            $fullUri = $this->sdk->getOptions()->getBaseUrl() . $uri;
            $response = $this->sdk->getHttpClient()->post($fullUri, [], $body);
            $this->checkHttpError($response);
            $response = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ResponseException('JSON parsing error: ' . $e->getMessage(), -98, null, ['details' => $e->getMessage()]);
        } catch (ResponseException $e) {
            throw new ResponseException('Response error: ' . $e->getMessage(), $e->getCode(), null, $e->getErrorDetails());
        }

        return $this->checkPaymentGatewayError($response);
    }

    private function checkHttpError(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            $body = $response->getBody()->getContents();
            $parsedBody = json_decode($body, true);

            if (isset($parsedBody['errors'])) {
                $errorData = $parsedBody;
            } else {
                $errorData = [
                    'data' => [],
                    'errors' => [
                        'message' => $response->getReasonPhrase(),
                        'code' => $statusCode,
                        'validations' => []
                    ]
                ];
            }

            throw new ResponseException($errorData['errors']['message'], $errorData['errors']['code'], null, $errorData);
        }
    }

    private function checkPaymentGatewayError(array $response): array
    {
        if (!empty($response['errors']) || empty($response['data'])) {
            $errorDetails = $response['errors'] ?? ['message' => 'Unknown error', 'code' => -1];
            throw new PaymentGatewayException($errorDetails['message'], $errorDetails['code'], null, $response);
        }
        return $response;
    }
}
