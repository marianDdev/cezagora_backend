<?php

namespace App\Providers;

use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
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
        Event::listen(MigrationsStarted::class, function (){
            if (config('databases.allow_disabled_pk')) {
                DB::statement('SET SESSION sql_require_primary_key=0');
            }
        });

        Event::listen(MigrationsEnded::class, function (){
            if (config('databases.allow_disabled_pk')) {
                DB::statement('SET SESSION sql_require_primary_key=1');
            }
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
