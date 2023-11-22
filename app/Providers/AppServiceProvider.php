<?php

namespace App\Providers;

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
        //        Component::macro('emit', function ($event) {
        //            $this->dispatch($event);
        //        });
        //
        //        Component::macro('dispatchBrowserEvent', function ($event) {
        //            $this->dispatch($event);
        //        });
    }
}
