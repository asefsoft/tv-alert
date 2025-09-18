<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            URL::forceScheme('https');
        }
        //        Component::macro('emit', function ($event) {
        //            $this->dispatch($event);
        //        });
        //
        //        Component::macro('dispatchBrowserEvent', function ($event) {
        //            $this->dispatch($event);
        //        });
    }
}
