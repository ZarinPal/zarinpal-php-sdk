<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\RefundRequest;
use ZarinPal\Sdk\Endpoint\GraphQL\ResponseTypes\RefundResponse;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use ZarinPal\Sdk\Options;

class RefundService
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

    public function refund(RefundRequest $request): array
    {
        $query = $request->toGraphQL();
        try {
            $response = $this->client->request('POST', $this->graphqlUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->options->getAccessToken(),
                    'Content-Type' => 'application/json',
                ],
                'body' => $query,
            ]);

            $responseData = $response->toArray();
            if (isset($responseData['errors'])) {
                throw new ResponseException('GraphQL query error: ' . json_encode($responseData['errors']));
            }

            return new RefundResponse($responseData['data']['resource']);
        } catch (RequestException $e) {
            throw new ResponseException('Request failed: ' . $e->getMessage(), 0, $e);
        }
    }
}


