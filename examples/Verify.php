<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\VerifyRequest;

$options = new Options([
    'merchant_id' => '1379bc04-196d-47bb-a8f0-0e969ec96179',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$verifyRequest = new VerifyRequest();
$verifyRequest->authority = 'A000000000000000000000000000ydq5y838'; // The authority code returned by the initial payment request
$verifyRequest->amount = 1000; // Amount in IRR

try {
    $response = $paymentGateway->verify($verifyRequest);
    echo "Payment Verified: \n";
    echo "Reference ID: " . $response->ref_id . "\n";
    echo "Code: " . $response->code . "\n";
} catch (\Exception $e) {
    echo 'Payment verification failed: ' . $e->getMessage();
}
