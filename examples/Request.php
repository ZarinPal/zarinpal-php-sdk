<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\RequestRequest;

$options = new Options([
    'sandbox' => false, // Enable sandbox mode
    'merchant_id' => '1379bc04-196d-47bb-a8f0-0e969ec96179',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$request = new RequestRequest();
$request->amount = 1000; // Amount in IRR
$request->description = 'Payment for order 12345';
$request->callback_url = 'http://localhost:8000/examples/verify.php';
$request->mobile = '09121234567'; // Optional
$request->email = 'test@example.com'; // Optional
//$request->wages = [
//    [
//        'iban' => 'IR130570028780010957775103',
//        'amount' =>5000,
//        'description' => 'تسهیم سود فروش'
//    ],
//    [
//        'iban' => 'IR670170000000352965862009',
//        'amount' => 5000,
//        'description' => 'تسهیم سود فروش به شخص دوم'
//    ]
//];

try {
    $response = $paymentGateway->request($request);
    $url = $paymentGateway->getRedirectUrl($response->authority);
    header('Location:'. $url);

} catch (\Exception $e) {
    echo 'Payment request failed: ' . $e->getMessage();
}
