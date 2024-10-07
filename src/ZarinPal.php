<?php

namespace ZarinPal\Sdk;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use ZarinPal\Sdk\Endpoint\GraphQL\TransactionService;
use ZarinPal\Sdk\Endpoint\GraphQL\RefundService;
use ZarinPal\Sdk\Endpoint\PaymentGateway\PaymentGateway;

final class ZarinPal
{
    private ClientBuilder $clientBuilder;
    private Options $options;
    private HttpMethodsClientInterface $httpClient;

    public const USER_AGENT = 'ZarinPalSdk/v.1.0 (php ' . PHP_VERSION . ')';

    public function __construct(Options $options = null)
    {
        $this->options = $options ?? new Options();
        $this->clientBuilder = $this->options->getClientBuilder();
        $this->clientBuilder->addPlugin(new BaseUriPlugin($this->options->getBaseUrl()));
        $this->clientBuilder->addPlugin(
            new HeaderDefaultsPlugin(
                [
                    'User-Agent' => self::USER_AGENT,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ]
            )
        );
        $this->httpClient = $this->clientBuilder->getHttpClient();
    }

    private function getClassName(): string
    {
        return basename(str_replace('\\', '/', __CLASS__));
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function paymentGateway(): PaymentGateway
    {
        return new PaymentGateway($this);
    }

    public function transactionService(): TransactionService
    {
        return new TransactionService($this->clientBuilder, $this->options);
    }

    public function refundService(): RefundService
    {
        return new RefundService($this->clientBuilder, $this->options);
    }

    public function getMerchantId(): string
    {
        return $this->options->getMerchantId();
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->httpClient;
    }

    public function setHttpClient(HttpMethodsClientInterface $client): void
    {
        $this->httpClient = $client;
    }
}
