<?php

declare(strict_types=1);

namespace App\Integrations\CoinApi;

use App\Domain\Interfaces\ClientInterface;
use GuzzleHttp\Client;

class CoinApiClient implements ClientInterface
{

    /** @var Client $client */
    private Client $client;

    /** @var string $apiKey */
    private string $apiKey;

    /** @var string $apiUrl */
    private string $apiUrl;

    public function __construct(string $apiKey, string $apiUrl)
    {
        $this->client = new Client();
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
    }

    public function get(string $path, array $params = []): array
    {
        $response = $this->client->request('GET',
            $this->apiUrl . $path,
            ['headers' => ['X-CoinAPI-Key' => $this->apiKey]]);

        if ($response->getStatusCode() === 200) {
            return \GuzzleHttp\json_decode($response->getBody(), true);
        }
    }

}