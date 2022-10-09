<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\RequestRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\UnverifiedRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\VerifyRequest;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;


$clientBuilder = new ClientBuilder();
$clientBuilder->addPlugin(new HeaderDefaultsPlugin([
    'Accept' => 'application/json',
]));


// usage
$options = new Options([
    'client_builder' => $clientBuilder,
    'merchant_id' => 'a738fc08-1e83-4928-bf90-08936ea6e1e2',
]);

$sdk = new ZarinPal($options);


$request = new RequestRequest();
$request->amount = 2000;
//$request->merchantId = 'a738fc08-1e83-4928-bf90-08936ea6e1e2';
$request->description = 'test';
$request->callback_url = 'https://tehran.ir';
$request->mobile = '09370000000';
$request->email = 'a@b.c';

$verify = new VerifyRequest();
$verify->amount = 15000;
$verify->authority = 'A00000000000000000000000000123456';

$unverified = new UnverifiedRequest();


$response = $sdk->paymentGateway()->request($request);
$response2 = $sdk->paymentGateway()->verify($verify);
$response3 = $sdk->paymentGateway()->unverified($unverified);

die(print_r($response) . print_r($response2) . print_r($response3));