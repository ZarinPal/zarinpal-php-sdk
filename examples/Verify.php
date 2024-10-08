<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\VerifyRequest;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;

$clientBuilder = new ClientBuilder();
$clientBuilder->addPlugin(new HeaderDefaultsPlugin([
    'Accept' => 'application/json',
]));

$options = new Options([
    'client_builder' => $clientBuilder,
    'sandbox' => false,
    'merchant_id' => '67887a6d-e2f8-4de2-86b1-8db27bc171b5',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$authority = filter_input(INPUT_GET, 'Authority', FILTER_SANITIZE_STRING);
$status = filter_input(INPUT_GET, 'Status', FILTER_SANITIZE_STRING);

if ($status === 'OK') {

    $amount = getAmountFromDatabase($authority); // تابع فرضی برای دریافت مبلغ از دیتابیس

    if ($amount) {
        $verifyRequest = new VerifyRequest();
        $verifyRequest->authority = $authority;
        $verifyRequest->amount = $amount;

        try {
            $response = $paymentGateway->verify($verifyRequest);

            if ($response->code === 100) {
                echo "Payment Verified: \n";
                echo "Reference ID: " . $response->ref_id . "\n";
                echo "Card PAN: " . $response->card_pan . "\n";
                echo "Fee: " . $response->fee . "\n";
            } else if ($response->code === 101) {
                echo "Payment already verified: \n";
            } else {
                echo "Transaction failed with code: " . $response->code;
            }

        } catch (ResponseException $e) {
            echo 'Payment Verification Failed: ' . $e->getErrorDetails();
        } catch (\Exception $e) {
            echo 'Payment Error: ' . $e->getMessage();
        }
    } else {
        echo 'No Matching Transaction Found For This Authority Code.';
    }
} else {
    echo 'Transaction was cancelled or failed.';
}
