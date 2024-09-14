<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\Endpoint\GraphQL\TransactionService;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\TransactionListRequest;

$options = new Options([
    'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZWU3MjgzZDQ0MzI1ZTJlMDdkODdhM2MzMDc2MzUzMWExZTYyZDI4OWMzOGMxZWY2ODI0NmRkZWZkYTRjZmNlMzY5NjVkMDQ0YjA1ZTMwNGMiLCJpYXQiOjE3MjQ2MTY3MjcuMTU5NTU5LCJuYmYiOjE3MjQ2MTY3MjcuMTU5NTYyLCJleHAiOjE4ODIzODMxMjcuMTIwOTkzLCJzdWIiOiIxMjE3NDYiLCJzY29wZXMiOltdfQ.L7CXjwVQQ0Pm0Ou4-7ALmKXZNHxUvrEvtvwe8i2H_zbcHTUZE51Gzd-wuO5gci09RalsshrOOwZ0UUZfCczuY8P8PfZTvvo5P6pzu6uhiU5FsEgyb8LNyyRakDXDkIekmyfDC-l3Y2dveBG2uAEfg4TflqjSJ-XIgeu4e9l8rnhWC91FS7d854aEqc7anpEbtetQG2gRSbAGgIWq6PA8laanX1Cj0eImUhsdG2B6raX4jTLfmn8bZ4bSmVNbTmgp7ltNGTLlU4ESbYCk79XhcUnGfYt59aeV0P_U82OVIXG0FBhKqrI4p8yJHbcObJgmSymLiZesZXlGfUST6I9u3fsaFCxd3BBFelrI84t6mTyabAEq7eGPJOlIu7pZbHtu3xCoNJoUKjPvltel1Ua25ENuY9GZa2rMFKl1hvSpkZYpJf9ZrYO7lQhxAoOufW-z9YehPD4axRQybVCFRYQ-Co1FczDU7RQPQ91-QBy34Z98Nj8qtl3pg00QieXLLHJHq2l5_ePzXb6-uLnQqIcpu2Sjrm9zObJkwcZ6pqASd4PVHDd76O27hDQ46q_Au3bQ5lm1MphSQ1yST5kpFNaTp8e0TvmkV8URsv8O0Ll9fT16Mf0faCgydtSv6K7saySnB6egs8MaB5qlMZf6oO1FcoJ3Cv9DJm56AxmYFYdYEgU',
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
