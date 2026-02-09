<?php

namespace App\Providers;

use App\Models\State;
use App\Models\ProjectRole;
use App\Models\ProjectType;
use App\Models\CustomerCode;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);
//        URL::forceScheme('https');

        View::composer('basicUser.layout.main', function ($view) {
            $view->with('customer_codes', CustomerCode::all());
            $view->with('roles', ProjectRole::all());
            $view->with('states_list', State::all());
            $view->with('types', ProjectType::all());
        });
    }
}
