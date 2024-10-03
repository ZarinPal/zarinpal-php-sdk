<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ZarinPal\Sdk\Options;
use Http\Mock\Client as MockClient;

class BaseTestCase extends TestCase
{
    protected $mockClient;
    protected $options;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = new MockClient();
        $this->options = new Options([
            'access_token' => 'mock-access-token',
            'merchant_id' => '67887a6d-e2f8-4de2-86b1-8db27bc171b5',
        ]);
    }

    protected function getMockClient(): MockClient
    {
        return $this->mockClient;
    }

    protected function getOptions(): Options
    {
        return $this->options;
    }
}
