<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Integrations\CoinApi\CoinApiService;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * @return array
     */
    public function getAssetsValue() : array
    {
        $user = request()->user();
        $userAssets = $user->assets()->get(['label', 'currency_code', 'value']);
        $amountsByLabels = $this->getAmountsByLabels($userAssets);
        $rates = $this->service->getExchangeRatesToUsd();

        return $this->getValuesInUsd($amountsByLabels, $rates);
    }

    /**
     * @param Collection $collection
     * @return array
     */
    private function getAmountsByLabels(Collection $collection): array
    {
        $result = [];

        foreach ($collection as $asset) {

            if (isset($result[$asset->label][$asset->currency_code]))
            {
                $result[$asset->label][$asset->currency_code] += $asset->value;
            }
            else
            {
                $result[$asset->label][$asset->currency_code] = $asset->value;
            }
        }

        return $result;
    }

    /**
     * @param array $amountsByLabels
     * @param array $rates
     * @return array
     */
    private function getValuesInUsd(array $amountsByLabels, array $rates): array
    {
        foreach ($amountsByLabels as $label => $currencies)
        {
            foreach ($currencies as $currency => $amount)
            {

                $amountInUsd = $amount * $rates[$currency]['rate'];
                $amountsByLabels[$label][$currency] = $amountInUsd;

                if (isset($amountsByLabels['TOTALS'][$currency]))
                {
                    $amountsByLabels['TOTALS'][$currency] += $amountInUsd;
                }
                else
                {
                    $amountsByLabels['TOTALS'][$currency] = $amountInUsd;
                }
            }
        }

        return $amountsByLabels;
    }
}
