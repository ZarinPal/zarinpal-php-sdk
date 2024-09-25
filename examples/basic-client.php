<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\RequestRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\UnverifiedRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\VerifyRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\ReverseRequest;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\InquiryRequest;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;

$clientBuilder = new ClientBuilder();
$clientBuilder->addPlugin(new HeaderDefaultsPlugin([
    'Accept' => 'application/json',
]));

// usage
$options = new Options([
    'client_builder' => $clientBuilder,
    'merchant_id' => 'x1379bc04-196d-47bb-a8f0-0e969ec96179',
]);

$sdk = new ZarinPal($options);

$request = new RequestRequest();
$request->amount = 10000;
$request->description = 'پرداخت تست';
$request->callback_url = 'https://tehran.ir';
$request->mobile = '09370000000';
$request->email = 'a@b.c';
$request->currency = 'IRT';
$request->cardPan = '5022291083818920';
$request->wages = [
    [
        'iban' => 'IR130570028780010957775103',
        'amount' =>5000,
        'description' => 'تسهیم سود فروش'
    ],
    [
        'iban' => 'IR670170000000352965862009',
        'amount' => 5000,
        'description' => 'تسهیم سود فروش به شخص دوم'
    ]
];

$verify = new VerifyRequest();
$verify->amount = 15000;
$verify->authority = 'A00000000000000000000000000123456';

$unverified = new UnverifiedRequest();

$reverseRequest = new ReverseRequest($options);
$reverseRequest->authority = 'A00000000000000000000000000123456';

$inquiryRequest = new InquiryRequest($options);
$inquiryRequest->authority = 'A00000000000000000000000000123456';

try {
    $response = $sdk->paymentGateway()->request($request);
    // نمایش نتیجه در صورت موفقیت
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (ResponseException $e) {
    // خطا به صورت JSON برمی‌گردد، بدون اینکه برنامه قطع شود
    echo "Error: " . $e->getMessage();
} catch (Exception $e) {
    // هندل کردن دیگر خطاهای غیرمنتظره
    echo "Unexpected Error: " . $e->getMessage();
}
$response2 = $sdk->paymentGateway()->verify($verify);
$response3 = $sdk->paymentGateway()->unverified($unverified);
$response4 = $sdk->paymentGateway()->reverse($reverseRequest);
$response5 = $sdk->paymentGateway()->inquiry($inquiryRequest);

die(print_r($response) . print_r($response2) . print_r($response3) . print_r($response4) . print_r($response5));
