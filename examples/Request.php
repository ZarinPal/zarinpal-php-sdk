<?php

require_once __DIR__ . '/vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\RequestRequest;

$options = new Options([
    'sandbox' => false, // Enable sandbox mode
    'merchant_id' => 'your_merchant_id_here',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$request = new RequestRequest();
$request->amount = 10000; // Amount in IRR
$request->description = 'Payment for order 12345';
$request->callback_url = 'https://yourcallbackurl.com';
$request->mobile = '09121234567'; // Optional
$request->email = 'test@example.com'; // Optional

try {
    $response = $paymentGateway->request($request);
    echo "Payment Request Successful: \n";
    echo "Authority: " . $response->authority . "\n";
    echo "Payment URL: " . $paymentGateway->getRedirectUrl($response->authority) . "\n";
} catch (\Exception $e) {
    echo 'Payment request failed: ' . $e->getMessage();
}
