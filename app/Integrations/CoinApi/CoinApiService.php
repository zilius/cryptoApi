<?php
declare(strict_types=1);

namespace App\Integrations\CoinApi;

use Cache;

class CoinApiService
{

    /** @var CoinApiClient client */
    private CoinApiClient $client;

    /**
     * CoinApiService constructor.
     * @param CoinApiClient $client
     */
    public function __construct(CoinApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return array
     */
    public function getExchangeRatesToUsd(): array
    {
        $result = [];

        foreach (config('currencies.supported_currencies') as $currency) {
            if ($rate = Cache::get($currency)) {
                $result[$currency] = $rate;
            } else {
                $rate = $this->client->get("/v1/exchangerate/{$currency}/USD");
                Cache::put($currency, $rate, 3600);
            }
        }

        return $result;
    }

}