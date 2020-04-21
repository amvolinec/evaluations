<?php

namespace App\Providers;

use App\Evaluation;
use App\Observers\EvaluationObserver;
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
        Evaluation::observe(EvaluationObserver::class);
    }
}
