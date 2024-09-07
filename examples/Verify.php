<?php

require_once __DIR__ . '/vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\VerifyRequest;

$options = new Options([
    'merchant_id' => 'your_merchant_id_here',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$verifyRequest = new VerifyRequest();
$verifyRequest->authority = 'A00000000000000000000000000123456'; // The authority code returned by the initial payment request
$verifyRequest->amount = 15000; // Amount in IRR

try {
    $response = $paymentGateway->verify($verifyRequest);
    echo "Payment Verified: \n";
    echo "Reference ID: " . $response->ref_id . "\n";
    echo "Code: " . $response->code . "\n";
} catch (\Exception $e) {
    echo 'Payment verification failed: ' . $e->getMessage();
}
