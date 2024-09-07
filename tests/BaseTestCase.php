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
            'merchant_id' => '25fe4c36-66e4-11e9-a9e4-000c29344814',
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
