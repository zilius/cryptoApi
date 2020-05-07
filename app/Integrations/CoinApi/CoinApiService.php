<?php
declare(strict_types=1);

namespace App\Integrations\CoinApi;

class CoinApiService
{

    /** @var CoinApiClient client */
    private CoinApiClient $client;

    public function __construct(CoinApiClient $client)
    {
        $this->client = $client;
    }

    public function getExchangeRates()
    {

    }

}