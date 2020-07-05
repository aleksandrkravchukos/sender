<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SenderProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Services\SenderInterface',
            'App\Services\Sender',
        );
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
}
