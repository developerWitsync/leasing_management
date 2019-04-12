<?php

namespace App\Providers;

use App\Observers\UserSubscriptionObserver;
use App\User;
use App\Observers\UserObserver;
use App\UserSubscription;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class); //user observer..
        UserSubscription::observe(UserSubscriptionObserver::class); //user subscription observer..
		Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
