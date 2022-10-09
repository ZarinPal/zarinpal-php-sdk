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
    private const BASE_URL = 'pg/v4/payment/';
    private const REQUEST_URI = self::BASE_URL . 'request.json';
    private const VERIFY_URI = self::BASE_URL . 'verify.json';
    private const UNVERIFIED_URI = self::BASE_URL . 'unVerified.json';
    private ZarinPal $sdk;

    public function __construct(ZarinPal $sdk)
    {
        $this->sdk = $sdk;
    }


    /**
     * @param RequestTypes\RequestRequest $request
     * @return RequestResponse
     * @throws JsonException
     * @throws PaymentGatewayException
     * @throws ResponseException
     * @throws \Http\Client\Exception
     */
    public function request(RequestTypes\RequestRequest $request): RequestResponse
    {
        $this->fillMerchantId($request);
        $response = $this->httpHandler(self::REQUEST_URI, $request->toString());

        return new RequestResponse($response['data']);

    }

    private function fillMerchantId($request): void
    {
        if ($request->merchantId === null) {
            $request->merchantId = $this->sdk->getMerchantId();
        }
    }

    /**
     * @throws PaymentGatewayException
     * @throws ResponseException|\Http\Client\Exception
     */
    private function httpHandler(string $uri, string $body): array
    {
        try {
            $response = $this->sdk->getHttpClient()
                ->post($uri, [], $body);
            $this->checkHttpError($response);
            $response = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new ResponseException($e->getMessage(), -99, $e);
        }

        return $this->checkPaymentGatewayError($response);
    }

    /**
     * @throws ResponseException
     */
    private function checkHttpError(ResponseInterface $response): void
    {
        if (!in_array($response->getStatusCode(), [200, 400])) {
            throw new ResponseException('Http Error', -99, null);
        }
    }

    /**
     * @throws PaymentGatewayException
     */
    private function checkPaymentGatewayError($response)
    {
        if (!empty($response['errors']) || empty($response['data'])) {
            throw new PaymentGatewayException($response['errors']);
        }
        return $response;
    }

    /**
     * @param RequestTypes\VerifyRequest $request
     * @return VerifyResponse
     * @throws JsonException
     * @throws PaymentGatewayException
     * @throws ResponseException
     * @throws \Http\Client\Exception
     */
    public function verify(RequestTypes\VerifyRequest $request): VerifyResponse
    {
        $this->fillMerchantId($request);
        $response = $this->httpHandler(self::VERIFY_URI, $request->toString());

        return new VerifyResponse($response['data']);
    }

    /**
     * @param RequestTypes\UnverifiedRequest $request
     * @return UnverifiedResponse
     * @throws JsonException
     * @throws PaymentGatewayException
     * @throws ResponseException
     * @throws \Http\Client\Exception
     */
    public function unverified(RequestTypes\UnverifiedRequest $request): UnverifiedResponse
    {
        $this->fillMerchantId($request);
        $response = $this->httpHandler(self::UNVERIFIED_URI, $request->toString());

        return new UnverifiedResponse($response['data']);
    }
}