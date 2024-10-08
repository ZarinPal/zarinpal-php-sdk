<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\UnverifiedRequest;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;


$options = new Options([
    'merchant_id' => '67887a6d-e2f8-4de2-86b1-8db27bc171b5',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$unverifiedRequest = new UnverifiedRequest();

try {

    $response = $paymentGateway->unverified($unverifiedRequest);

    if ($response->code === 100) {
        if (empty($response->authorities)) {
            echo "No authorities found.\n";
        } else {
            foreach ($response->authorities as $transaction) {
                echo "Transaction Authority: " . $transaction['authority'] . "\n";
                echo "Amount: " . $transaction['amount'] . "\n";
                echo "Callback URL: " . $transaction['callback_url'] . "\n";
                echo "Referer: " . $transaction['referer'] . "\n";
                echo "Date: " . $transaction['date'] . "\n";
                echo "--------------------------\n";
            }
        }
    } else {
        echo "Failed to retrieve unverified transactions. Code: " . $response->code . "\n";
        echo "Message: " . $response->message . "\n";
    }
} catch (ResponseException $e) {
    echo 'Unverified inquiry failed: ' . $e->getMessage() . "\n";
    if ($e->getErrorDetails()) {
        echo 'Error Details: ' . json_encode($e->getErrorDetails()) . "\n";
    }
} catch (\Exception $e) {
    echo 'Unverified inquiry failed: ' . $e->getMessage();
}