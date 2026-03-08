<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Fallback default locale for URLs generated before middleware or in CLI/queue
        \Illuminate\Support\Facades\URL::defaults(['locale' => config('app.locale', 'vi')]);
    }
}
