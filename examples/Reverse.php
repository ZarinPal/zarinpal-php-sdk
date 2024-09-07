<?php

require_once __DIR__ . '/vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\ReverseRequest;

$options = new Options([
    'merchant_id' => 'your_merchant_id_here',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$reverseRequest = new ReverseRequest();
$reverseRequest->authority = 'A00000000000000000000000000123456'; // Authority from the original transaction

try {
    $response = $paymentGateway->reverse($reverseRequest);
    echo "Transaction Reversed: " . $response->status . "\n";
} catch (\Exception $e) {
    echo 'Transaction reversal failed: ' . $e->getMessage();
}
