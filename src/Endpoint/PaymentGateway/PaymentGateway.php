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
            $fullUri = $this->sdk->getOptions()->getBaseUrl() . $uri; // Use the correct base URL (sandbox or production)
            $response = $this->sdk->getHttpClient()->post($fullUri, [], $body);
            $this->checkHttpError($response);
            $response = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ResponseException('JSON parsing error: ' . $e->getMessage(), -98, $e);
        } catch (ResponseException $e) {
            throw $e; // Re-throw the original ResponseException to show exact status and message
        } catch (Exception $e) {
            throw new ResponseException('Request failed: ' . $e->getMessage(), -99, $e);
        }

        return $this->checkPaymentGatewayError($response);
    }

    private function checkHttpError(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            $body = $response->getBody()->getContents();
            $parsedBody = json_decode($body, true);

            if (isset($parsedBody['errors']['message'], $parsedBody['errors']['code'])) {
                $message = $parsedBody['errors']['message'];
                $code = $parsedBody['errors']['code'];
            } else {
                $message = 'HTTP Error: ' . $response->getReasonPhrase();
                $code = $statusCode;
            }

            // Create the error response as an array
            $errorResponse = [
                'http_status_code' => $statusCode,
                'error_code' => $code,
                'error_message' => $message,
            ];

            // Convert the error response to JSON
            $errorJson = json_encode($errorResponse, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

            // Instead of displaying the error, throw it as JSON
            throw new ResponseException($errorJson, $code);
        }
    }


    private function checkPaymentGatewayError(array $response): array
    {
        if (!empty($response['errors']) || empty($response['data'])) {
            $errorDetails = $response['errors'] ?? ['message' => 'Unknown error', 'code' => -1];
            throw new PaymentGatewayException($errorDetails);
        }
        return $response;
    }
}
