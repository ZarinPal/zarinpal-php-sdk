<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\InquiryRequest;

$options = new Options([
    'merchant_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$inquiryRequest = new InquiryRequest();
$inquiryRequest->authority = 'A000000000000000000000000000ydq5y838';

try {
    $response = $paymentGateway->inquiry($inquiryRequest);
    echo "Transaction Inquiry: \n";
    echo "Amount: " . $response->code . "\n";
    echo "Status: " . $response->message . "\n";
    echo "Status: " . $response->status . "\n";
} catch (\Exception $e) {
    echo 'Transaction inquiry failed: ' . $e->getMessage();
}
