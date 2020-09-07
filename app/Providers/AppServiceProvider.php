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
        $this->app
	    ->when(\Tymon\JWTAuth\Providers\Storage\Illuminate::class)
	    ->needs(\Illuminate\Contracts\Cache\Repository::class)
	    ->give(function () {
		    return cache()->store('jwt');
	    });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
