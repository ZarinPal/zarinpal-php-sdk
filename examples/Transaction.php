<?php

require_once __DIR__ . '/vendor/autoload.php';

use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\Endpoint\GraphQL\TransactionService;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\TransactionListRequest;

$options = new Options([
    'access_token' => 'your_access_token_here',
]);

$transactionService = new TransactionService($options);

$transactionRequest = new TransactionListRequest();
$transactionRequest->terminalId = '238';
$transactionRequest->filter = 'PAID'; // Optional filter: PAID, VERIFIED, TRASH, ACTIVE, REFUNDED

try {
    $transactions = $transactionService->getTransactions($transactionRequest);
    foreach ($transactions as $transaction) {
        echo "Transaction ID: " . $transaction->id . "\n";
        echo "Status: " . $transaction->status . "\n";
        echo "Amount: " . $transaction->amount . "\n";
        echo "Description: " . $transaction->description . "\n";
        echo "Created At: " . $transaction->created_at . "\n\n";
    }
} catch (\Exception $e) {
    echo 'Failed to retrieve transactions: ' . $e->getMessage();
}
