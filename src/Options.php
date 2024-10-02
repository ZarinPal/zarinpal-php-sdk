<?php

declare(strict_types=1);

namespace ZarinPal\Sdk;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Options
{
    private array $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'client_builder' => new ClientBuilder(),
            'uri_factory' => Psr17FactoryDiscovery::findUriFactory(),
            'base_url' => $this->arrayGet(getenv(), 'ZARINPAL_BASE_URL', 'https://payment.zarinpal.com'),
            'sandbox_base_url' => $this->arrayGet(getenv(), 'ZARINPAL_SANDBOX_BASE_URL', 'https://sandbox.zarinpal.com'),
            'merchant_id' => $this->arrayGet(getenv(), 'ZARINPAL_MERCHANT_KEY', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'),
            'graphql_url' => $this->arrayGet(getenv(), 'ZARINPAL_GRAPHQL_URL', 'https://next.zarinpal.com/api/v4/graphql/'),
            'access_token' => $this->arrayGet(getenv(), 'ZARINPAL_ACCESS_TOKEN', ''),
            'sandbox' => $this->arrayGet(getenv(), 'ZARINPAL_SANDBOX', 'false') === 'true',
        ]);

        $resolver->setAllowedTypes('client_builder', ClientBuilder::class);
        $resolver->setAllowedTypes('uri_factory', UriFactoryInterface::class);
        $resolver->setAllowedTypes('base_url', 'string');
        $resolver->setAllowedTypes('sandbox_base_url', 'string');
        $resolver->setAllowedTypes('merchant_id', 'string');
        $resolver->setAllowedTypes('graphql_url', 'string');
        $resolver->setAllowedTypes('access_token', 'string');
        $resolver->setAllowedTypes('sandbox', 'bool');
    }

    private function arrayGet(array $array, string $key, ?string $default = null): ?string
    {
        if (array_key_exists($key, $array) && $array[$key] !== '') {
            return $array[$key];
        }

        return $default;
    }

    public function getClientBuilder(): ClientBuilder
    {
        return $this->options['client_builder'];
    }

    public function getBaseUrl(): UriInterface
    {
        $url = $this->options['sandbox'] ? $this->options['sandbox_base_url'] : $this->options['base_url'];
        return $this->getUriFactory()->createUri($url);
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->options['uri_factory'];
    }

    public function getMerchantId(): string
    {
        return $this->options['merchant_id'];
    }

    public function getGraphqlUrl(): string
    {
        return $this->options['graphql_url'];
    }

    public function getAccessToken(): string
    {
        return $this->options['access_token'];
    }
}
