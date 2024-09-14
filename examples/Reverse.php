<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\ReverseRequest;

$options = new Options([
    'merchant_id' => '1379bc04-196d-47bb-a8f0-0e969ec96179',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$reverseRequest = new ReverseRequest();
$reverseRequest->authority = 'A000000000000000000000000000ydq5y838'; // Authority from the original transaction

try {
    $response = $paymentGateway->reverse($reverseRequest);
    echo "Transaction Reversed: " . $response->code . "\n";
    echo "Transaction Reversed: " . $response->message . "\n";
} catch (\Exception $e) {
    echo 'Transaction reversal failed: ' . $e->getMessage();
}
