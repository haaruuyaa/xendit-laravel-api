<?php

namespace Haaruuyaa\XenditLaravelApi\Providers;

use Haaruuyaa\XenditLaravelApi\Controllers\EwalletController;
use Haaruuyaa\XenditLaravelApi\Facades\Ewallet;
use Illuminate\Support\ServiceProvider;

class EwalletProviders extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('ewallet', function () {
            return new EwalletController();
        });

        // Register the facade alias
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Ewallet', Ewallet::class);
    }
}