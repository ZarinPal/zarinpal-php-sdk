<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL;

use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\TransactionListRequest;
use ZarinPal\Sdk\Endpoint\GraphQL\ResponseTypes\TransactionListResponse;
use ZarinPal\Sdk\Options;

class TransactionService extends BaseGraphQLService
{
    public function __construct(ClientBuilder $clientBuilder, Options $options)
    {
        parent::__construct($clientBuilder, $options);
    }

    public function getTransactions(TransactionListRequest $request): array
    {
        $query = $request->toGraphQL();

        $response = $this->httpHandler($this->graphqlUrl, $query);

        $transactions = [];
        foreach ($response['data']['Session'] as $data) {
            $transactions[] = new TransactionListResponse($data);
        }

        return $transactions;
    }
}
