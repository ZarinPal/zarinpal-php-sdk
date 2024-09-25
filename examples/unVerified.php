<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\UnverifiedRequest;

$options = new Options([
    'merchant_id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$unverifiedRequest = new UnverifiedRequest();

try {

    $response = $paymentGateway->unverified($unverifiedRequest);

    if ($response->code === 100) {
        foreach ($response->authorities as $transaction) {
            echo "Transaction Authority: " . $transaction['authority'] . "\n";
            echo "Amount: " . $transaction['amount'] . "\n";
            echo "Callback URL: " . $transaction['callback_url'] . "\n";
            echo "Referer: " . $transaction['referer'] . "\n";
            echo "Date: " . $transaction['date'] . "\n";
            echo "--------------------------\n";
        }
    } else {
        echo "Failed to retrieve unverified transactions. Code: " . $response->code . "\n";
        echo "Message: " . $response->message . "\n";
    }
} catch (\Exception $e) {
    echo 'Unverified inquiry failed: ' . $e->getMessage();
}