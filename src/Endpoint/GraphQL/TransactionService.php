<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\TransactionListRequest;
use ZarinPal\Sdk\Endpoint\GraphQL\ResponseTypes\TransactionListResponse;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\Options;

class TransactionService
{
    private Client $client;
    private Options $options;
    private string $graphqlUrl;

    public function __construct(Options $options)
    {
        $this->client = new Client(); // Instantiate Guzzle client
        $this->options = $options;
        $this->graphqlUrl = $options->getGraphqlUrl();
    }

    public function getTransactions(TransactionListRequest $request): array
    {
        $query = $request->toGraphQL();
        try {
            $response = $this->client->post($this->graphqlUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->options->getAccessToken(),
                    'Content-Type' => 'application/json',
                ],
                'body' => $query,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            if (isset($responseData['errors'])) {
                throw new ResponseException('GraphQL query error: ' . json_encode($responseData['errors']));
            }

            $transactions = [];
            foreach ($responseData['data']['Session'] as $data) {
                $transactions[] = new TransactionListResponse($data);
            }

            return $transactions;
        } catch (RequestException $e) {
            throw new ResponseException('Request failed: ' . $e->getMessage(), 0, $e);
        }
    }
}

