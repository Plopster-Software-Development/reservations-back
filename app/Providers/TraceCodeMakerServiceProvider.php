<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Plopster\TraceCodeMaker\TraceCodeMaker;

class TraceCodeMakerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('tracecodemaker', function ($app) {
            return new TraceCodeMaker();
        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
