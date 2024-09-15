<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\ReverseRequest;

$options = new Options([
    'merchant_id' => 'efb22724-c8d3-46c8-a4e7-f87b0e07748e',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$reverseRequest = new ReverseRequest();
$reverseRequest->authority = 'A000000000000000000000000000opo6w6y8'; // Authority from the original transaction

try {
    $response = $paymentGateway->reverse($reverseRequest);
    echo "Transaction Reversed: " . $response->code . "\n";
    echo "Transaction Reversed: " . $response->message . "\n";
} catch (\Exception $e) {
    echo 'Transaction reversal failed: ' . $e->getMessage();
}
