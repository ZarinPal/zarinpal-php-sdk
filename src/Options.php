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
        $resolver->setDefaults(
            [
                'client_builder' => new ClientBuilder(),
                'uri_factory' => Psr17FactoryDiscovery::findUriFactory(),
                'base_url' => $this->arrayGet(getenv(), 'ZARINPAL_MERCHANT_URL', 'https://api.zarinpal.com/'),
                'merchant_id' => $this->arrayGet(getenv(), 'ZARINPAL_MERCHANT_KEY', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx')
            ]
        );

        $resolver->setAllowedTypes('client_builder', ClientBuilder::class);
        $resolver->setAllowedTypes('uri_factory', UriFactoryInterface::class);
        $resolver->setAllowedTypes('base_url', 'string');
        $resolver->setAllowedTypes('merchant_id', 'string');
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
        return $this->getUriFactory()->createUri($this->options['base_url']);
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->options['uri_factory'];
    }

    public function getMerchantId(): string
    {
        return $this->options['merchant_id'];
    }
}

