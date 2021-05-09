<?php

namespace App\Providers;

use App\Evaluation;
use App\Group;
use App\Http\View\Composers\GroupsComposer;
use App\Observers\EvaluationObserver;
use App\Revisions\MakeRevision;
use Illuminate\Support\Facades\View;
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

        View::composer('partials.groups.*',GroupsComposer::class);

        $this->app->singleton('Version', function($app){
            return new MakeRevision();
        });

    }
}
