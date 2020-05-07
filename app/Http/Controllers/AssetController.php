<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Asset;
use App\Integrations\CoinApi\CoinApiService;

class AssetController extends Controller
{

    /** @var CoinApiService $client */
    private CoinApiService $service;

    /**
     * AssetController constructor.
     * @param CoinApiService $coinApiServiceProvider
     */
    public function __construct(CoinApiService $coinApiServiceProvider)
    {
        $this->service = $coinApiServiceProvider;
    }

    public function getAssetsValue(string $currency = 'USD')
    {
        $assets = Asset::where('user_id',5)->get();

        dd($assets);

        $this->service->getExchangeRates();
    }
}
