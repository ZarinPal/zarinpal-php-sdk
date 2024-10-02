<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\RefundRequest;

$clientBuilder = new ClientBuilder(30);

$options = new Options([
    'client_builder' => $clientBuilder,
    'access_token' => 'your_access_token', // Access token بدون Bearer
]);

$zarinpal = new ZarinPal($options);

$refundService = $zarinpal->refundService();
$refundRequest = new RefundRequest();
$refundRequest->sessionId = '580868147';
$refundRequest->amount = 20000; // Amount in IRR
$refundRequest->description = 'Refund for order 12345';
$refundRequest->method = 'CARD'; // Method: CARD for instant, PAYA for regular
$refundRequest->reason = 'CUSTOMER_REQUEST'; // Reason for refund

try {
    $response = $refundService->refund($refundRequest);
    echo "Refund Processed: \n";
    echo "Transaction ID: " . $response->id . "\n";
    echo "Terminal ID: " . $response->terminalId . "\n";
    echo "Refund Amount: " . $response->timeline['refund_amount'] . "\n";
    echo "Refund Time: " . $response->timeline['refund_time'] . "\n";
    echo "Refund Status: " . $response->timeline['refund_status'] . "\n";
} catch (\Exception $e) {
    echo 'Refund failed: ' . $e->getMessage();
}
