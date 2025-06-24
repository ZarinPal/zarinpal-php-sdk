<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\Endpoint\PaymentGateway\RequestTypes\FeeCalculationRequest;

$options = new Options([
    'merchant_id' => '67887a6d-e2f8-4de2-86b1-8db27bc171b5',
]);

$zarinpal = new ZarinPal($options);
$paymentGateway = $zarinpal->paymentGateway();

$feeCalculationRequest = new FeeCalculationRequest();
$feeCalculationRequest->amount = 5000000;
$feeCalculationRequest->currency = 'IRR'; // Optional - IRR or IRT

try {
    $response = $paymentGateway->feeCalculation($feeCalculationRequest);
    echo "Fee Calculation Result: \n";
    echo "Amount: " . $response->amount . "\n";
    echo "Fee: " . $response->fee . "\n";
    echo "Fee Type: " . $response->fee_type . "\n";
    echo "Suggested Amount: " . $response->suggested_amount . "\n";
    echo "Code: " . $response->code . "\n";
    echo "Message: " . $response->message . "\n";

} catch (ResponseException $e) {
    echo 'Fee Calculation Error: ' . $e->getMessage() . "\n";
    if ($e->getErrorDetails()) {
        echo 'Error Details: ' . json_encode($e->getErrorDetails()) . "\n";
    }
} catch (\Exception $e) {
    echo 'Fee Calculation Error: ' . $e->getMessage();
} 