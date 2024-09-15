<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\Endpoint\GraphQL\RefundService;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\RefundRequest;

$options = new Options([
    'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNjA5NWNjOWYzNDUxZjMyZmQ3ODYxYTFhZWEyM2QwZGJkNWYxMjY0YmFhNDVkYjJjZjJlNzc2MGMzNTMwMzY5MzAyZjdlYTk1OGY0MDM5NmEiLCJpYXQiOjE3MjYzOTMyMjMuNTI5MTM2LCJuYmYiOjE3MjYzOTMyMjMuNTI5MTM4LCJleHAiOjE4ODQxNTk2MjMuNDk1OTMzLCJzdWIiOiIxNDQxNDU3Iiwic2NvcGVzIjpbXX0.tluhzZ7aT7I3x4fD07793nZfMgw11fyHy3x5igPrthVBX7KduuoM4j0KHWszOH9C56me7FJFOX-wm8mf-AILv8W0Up26KVIKRFThDRaOMlnjQ11b0aD9vkZdEoQP6Lb8Z1UsSIq699cRtpbunAPbVj6kr5Cq9aiPVqiUKl_93qshC8kTHpjoIAwvG3614AXQNLXgKhCVmv8Ky8TbV3nqW5AuSB20WTNdouz0ASiwKlU3B3rKsIZAAJ5toe-WGhMjReeIqA_vjX4BN-XmDuthYXOtQqXu4z6UG_M_8B_z2ihliwTAFL1qw3YBSo7u5Pr0U2pggLYsql1IcKVtNHj2AaMefkVd4NEuy4rgcNjK1JPCTyOoE4lLR7AdwlgBxMQ8ZXHDLPE2W75TKR48sh00pOhf6B_f0ptF6aM0_FP4DdSH87F4OdE8GRbUBAwVmVhkMCelfpc1pbAvKB9v3SpojupqkPB4-Xb_Qrd7-VP0aiXhxN0s5oKhszci5OihkfqjDGXaVjQCS3DzjveD0JUQBw-NCFtPj6BOskGVpAWjPKOEpXnQJkYmbU8tvfq_xIUCYvextEOMi6BvwyJSxAxSJbAY5hxY10sdblobaVxmaD-xcR8vwYwebq1JZx4v5WlhFLK2RNNg6g6sEqkXMZTDCcXIRIKcPzCMOuiHEEKx9Pk',
]);

$refundService = new RefundService($options);

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
    echo 'Refund failed: ' . $response;
}
