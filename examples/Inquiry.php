<?php

require_once __DIR__ . '/vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\InquiryRequest;

$options = new Options([
    'merchant_id' => 'your_merchant_id_here',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$inquiryRequest = new InquiryRequest();
$inquiryRequest->authority = 'A00000000000000000000000000123456'; // Authority from the original transaction

try {
    $response = $paymentGateway->inquiry($inquiryRequest);
    echo "Transaction Inquiry: \n";
    echo "Amount: " . $response->amount . "\n";
    echo "Status: " . $response->status . "\n";
} catch (\Exception $e) {
    echo 'Transaction inquiry failed: ' . $e->getMessage();
}
