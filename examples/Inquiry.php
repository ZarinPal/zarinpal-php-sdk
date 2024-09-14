<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\InquiryRequest;

$options = new Options([
    'merchant_id' => '1379bc04-196d-47bb-a8f0-0e969ec96179',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$inquiryRequest = new InquiryRequest();
$inquiryRequest->authority = 'A000000000000000000000000000ydq5y838'; // Authority from the original transaction

try {
    $response = $paymentGateway->inquiry($inquiryRequest);
    echo "Transaction Inquiry: \n";
    echo "Amount: " . $response->code . "\n";
    echo "Status: " . $response->message . "\n";
    echo "Status: " . $response->status . "\n";
} catch (\Exception $e) {
    echo 'Transaction inquiry failed: ' . $e->getMessage();
}
