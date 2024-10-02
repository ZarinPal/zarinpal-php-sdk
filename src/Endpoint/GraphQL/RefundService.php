<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL;

use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\Endpoint\GraphQL\RequestTypes\RefundRequest;
use ZarinPal\Sdk\Endpoint\GraphQL\ResponseTypes\RefundResponse;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use Psr\Http\Message\ResponseInterface;
use JsonException;
use Exception;

class RefundService
{
    private ClientBuilder $clientBuilder;
    private Options $options;
    private string $graphqlUrl;

    public function __construct(ClientBuilder $clientBuilder, Options $options)
    {
        $this->clientBuilder = $clientBuilder;
        $this->options = $options;
        $this->graphqlUrl = $options->getGraphqlUrl();
    }

    public function refund(RefundRequest $request): RefundResponse
    {
        $query = $request->toGraphQL();

        $response = $this->httpHandler($this->graphqlUrl, $query);

        return new RefundResponse($response['data']['resource']);
    }

    private function httpHandler(string $uri, string $body): array
    {
        try {
            $httpClient = $this->clientBuilder->getHttpClient();

            $response = $httpClient->post($uri, [
                'User-Agent' => sprintf('%sSdk/v.0.1 (php %s)', $this->getClassName(), PHP_VERSION),
                'Authorization' => 'Bearer ' . $this->options->getAccessToken(),
                'Content-Type' => 'application/json',
            ], $body);

            $this->checkHttpError($response);

            $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        } catch (JsonException $e) {
            throw new ResponseException('JSON parsing error: ' . $e->getMessage(), -98, null, ['details' => $e->getMessage()]);
        } catch (ResponseException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new ResponseException('Request failed: ' . $e->getMessage(), -99, null, ['details' => $e->getMessage()]);
        }

        return $this->checkGraphQLError($responseData);
    }

    private function checkHttpError(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            $body = $response->getBody()->getContents();
            $parsedBody = json_decode($body, true);

            $errorData = [
                'data' => [],
                'errors' => [
                    'message' => $response->getReasonPhrase(),
                    'code' => $statusCode,
                    'details' => $parsedBody ?? []
                ]
            ];

            throw new ResponseException($errorData['errors']['message'], $errorData['errors']['code'], null, $errorData);
        }
    }

    private function checkGraphQLError(array $response): array
    {
        if (isset($response['errors']) || empty($response['data'])) {
            $errorDetails = $response['errors'] ?? ['message' => 'Unknown error', 'code' => -1];
            throw new ResponseException('GraphQL query error: ' . json_encode($errorDetails), $errorDetails['code']);
        }

        return $response;
    }

    private function getClassName(): string
    {
        return basename(str_replace('\\', '/', __CLASS__));
    }
}
