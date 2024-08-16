<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Factory::class, function ($app) {
            $firebaseConfig = config('firebase.credentials');
//
            // Load the Firebase credentials from the configuration
             $factory = (new Factory)->withServiceAccount($firebaseConfig);


return  $factory;

        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Schema::defaultStringLength(191);
    }
}
