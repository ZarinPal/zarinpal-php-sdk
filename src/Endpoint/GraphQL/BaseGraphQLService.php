<?php

namespace ZarinPal\Sdk\Endpoint\GraphQL;

use ZarinPal\Sdk\ClientBuilder;
use ZarinPal\Sdk\Options;
use ZarinPal\Sdk\HttpClient\Exception\ResponseException;
use Psr\Http\Message\ResponseInterface;
use JsonException;
use ZarinPal\Sdk\ZarinPal;

class BaseGraphQLService
{
    protected ClientBuilder $clientBuilder;
    protected Options $options;
    protected string $graphqlUrl;

    public function __construct(ClientBuilder $clientBuilder, Options $options)
    {
        $this->clientBuilder = $clientBuilder;
        $this->options = $options;
        $this->graphqlUrl = $options->getGraphqlUrl();
    }

    protected function httpHandler(string $uri, string $body): array
    {
        try {
            $httpClient = $this->clientBuilder->getHttpClient();
            $response = $httpClient->post($uri, [
                'User-Agent' => ZarinPal::USER_AGENT,
                'Authorization' => 'Bearer ' . $this->options->getAccessToken(),
                'Content-Type' => 'application/json',
            ], $body);

            $this->checkHttpError($response);

            $responseData = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        } catch (JsonException $e) {
            throw new ResponseException('JSON parsing error: ' . $e->getMessage(), $e->getCode(), null, ['details' => $e->getMessage()]);
        } catch (ResponseException $e) {
            throw new ResponseException('Response error: ' . $e->getMessage(), $e->getCode(), null, $e->getErrorDetails());
        }

        return $this->checkGraphQLError($responseData);
    }

    protected function checkHttpError(ResponseInterface $response): void
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

    protected function checkGraphQLError(array $response): array
    {
        if (isset($response['errors']) || empty($response['data'])) {
            $errorDetails = $response['errors'] ?? ['message' => 'Unknown error', 'code' => -1];
            throw new ResponseException('GraphQL query error: ' . json_encode($errorDetails), $errorDetails['code']);
        }

        return $response;
    }

    protected function getClassName(): string
    {
        return basename(str_replace('\\', '/', static::class));
    }
}

