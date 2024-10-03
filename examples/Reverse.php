<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\ReverseRequest;

$options = new Options([
    'merchant_id' => '67887a6d-e2f8-4de2-86b1-8db27bc171b5',
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
