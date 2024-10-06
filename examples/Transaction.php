<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\ZarinPal;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\TransactionListRequest;

$clientBuilder = new ClientBuilder();

$options = new Options([
    'client_builder' => $clientBuilder,
    'access_token' => 'your access token', // Access token without Bearer
]);

$zarinpal = new ZarinPal($options);

$transactionService = $zarinpal->transactionService();

$transactionRequest = new TransactionListRequest();
$transactionRequest->terminalId = '349555';
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
   } catch (Exception $e) {
 echo "General Error: " . $e->getMessage();
}
