<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL;

use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\RefundRequest;
use ZarinPal\Sdk\Endpoint\GraphQL\ResponseTypes\RefundResponse;
use ZarinPal\Sdk\Options;

class RefundService extends BaseGraphQLService
{
    public function __construct(ClientBuilder $clientBuilder, Options $options)
    {
        parent::__construct($clientBuilder, $options);
    }

    public function refund(RefundRequest $request): RefundResponse
    {
        $query = $request->toGraphQL();

        $response = $this->httpHandler($this->graphqlUrl, $query);

        return new RefundResponse($response['data']['resource']);
    }
}
