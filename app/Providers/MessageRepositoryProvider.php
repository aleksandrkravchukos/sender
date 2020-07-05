<?php

namespace App\Providers;

use App\Repository\MessageRepository;
use Illuminate\Support\ServiceProvider;

class MessageRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repository\MessageRepositoryInterface',
            'App\Repository\MessageRepository'
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
