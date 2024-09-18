<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\Endpoint\GraphQL\TransactionService;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\TransactionListRequest;

$options = new Options([
    'access_token' => 'your access token', // Access token without Bearer
]);

$transactionService = new TransactionService($options);

$transactionRequest = new TransactionListRequest();
$transactionRequest->terminalId = '250';
$transactionRequest->filter = 'PAID'; // Optional filter: PAID, VERIFIED, TRASH, ACTIVE, REFUNDED

try {
    $transactions = $transactionService->getTransactions($transactionRequest);

    $transactionArray = [];
    foreach ($transactions as $transaction) {
        $transactionArray[] = [
            'Transaction ID' => $transaction->id,
            'Status' => $transaction->status,
            'Amount' => $transaction->amount,
            'Description' => $transaction->description,
            'Created At' => $transaction->created_at,
        ];
    }

    echo json_encode($transactionArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (ResponseException $e) {
    echo "GraphQL Error: " . $e->getMessage();
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage();
}
