<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Asset;
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
    public function getAssetsValue(): array
    {
        $user = request()->user();
        $userAssets = $user->assets()->get(['label', 'currency_code', 'value']);
        $amountsByLabels = $this->getAmountsByLabels($userAssets);
        $rates = $this->service->getExchangeRatesToUsd();

        return $this->getValuesInUsd($amountsByLabels, $rates);
    }

    public function get(): ?array
    {
        $label = request('label');
        $user = request()->user();

        if (!empty($label)) {
            return $this->getAmountsByLabels($userAssets = $user->assets()->where('label', $label)->get(['label', 'currency_code', 'value']));
        }

        return $userAssets = $this->getAmountsByLabels($user->assets()->get(['label', 'currency_code', 'value']));
    }

    public function post(): string
    {
        $user = request()->user();
        $label = request('label');
        $currency_code = request('currency_code');
        $value = request('value');

        if (empty($label)) {
            return "Please select label";
        }

        if (!in_array($currency_code, config('currencies.supported_currencies'))) {
            return implode(' ', config('currencies.supported_currencies')) . " are supported";
        }

        if ($value < 0) {
            return "Value must be positive";
        }

        $exist = Asset::where('user_id', $user->id)->where('label', $label)->where('currency_code',$currency_code)->first();
        if ($exist) {
            return "Asset with this label already exist, please use PUT if you want to deposit currency";
        }

        $asset = new Asset();
        $asset->user_id = $user->id;
        $asset->label = $label;
        $asset->currency_code = $currency_code;
        $asset->value = $value;
        $asset->save();

        return ($asset->toJson());
    }

    public function delete(): string
    {
        $user = request()->user();
        $label = request('label');

        if (empty($label)) {
            return "Select label to delete";
        }

        return (string)Asset::where('user_id', $user->id)->where('label', $label)->delete();
    }

    public function put()
    {
        $user = request()->user();
        $label = request('label');
        $currency_code = request('currency_code');
        $value = request('value');

        if (empty($label)) {
            return "Please select label";
        }

        if (!in_array($currency_code, config('currencies.supported_currencies'))) {
            return implode(' ', config('currencies.supported_currencies')) . " are supported";
        }

        $asset = Asset::where('user_id', $user->id)->where('label', $label)->where('currency_code', $currency_code)->first();

        if (empty($asset)) {
            return 'Asset does not exist, please add it';
        }

        if ($asset->value + $value < 0) {
            return "You dont have enough currency";
        }

        $asset->value = $asset->value + $value;
        $asset->save();

        return $asset->value;

    }

    /**
     * @param Collection $collection
     * @return array
     */
    private function getAmountsByLabels(Collection $collection): array
    {
        $result = [];

        foreach ($collection as $asset) {

            if (isset($result[$asset->label][$asset->currency_code])) {
                $result[$asset->label][$asset->currency_code] += $asset->value;
            } else {
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
        foreach ($amountsByLabels as $label => $currencies) {
            foreach ($currencies as $currency => $amount) {

                $amountInUsd = $amount * $rates[$currency]['rate'];
                $amountsByLabels[$label][$currency] = $amountInUsd;

                if (isset($amountsByLabels['TOTALS'][$currency])) {
                    $amountsByLabels['TOTALS'][$currency] += $amountInUsd;
                } else {
                    $amountsByLabels['TOTALS'][$currency] = $amountInUsd;
                }
            }
        }

        return $amountsByLabels;
    }
}
