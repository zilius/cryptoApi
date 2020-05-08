<?php

declare(strict_types=1);

namespace App\Providers;

use App\Integrations\CoinApi\CoinApiClient;
use App\Integrations\CoinApi\CoinApiService;
use Config;
use Illuminate\Support\ServiceProvider;

class CoinApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CoinApiService::class, function ($app) {
            return new CoinApiService(
                new CoinApiClient(
                    Config::get('integrations.coin_api.key'),
                    Config::get('integrations.coin_api.url')
                )
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function provides()
    {
        return [
            CoinApiService::class,
        ];
    }
}
