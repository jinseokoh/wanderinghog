<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Routing\UrlGenerator;
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
        if ($this->app->environment() === 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $urlGenerator)
    {
        if ($this->app->environment() === 'production') {
            $urlGenerator->forceScheme('https');
            $this->app->singleton(
                ElasticsearchRepository::class,
                function (Container $app) {
                    return $app->make(AwsElasticsearchRepository::class);
                }
            );
        } else {
            $this->app->singleton(
                ElasticsearchRepository::class,
                function (Container $app) {
                    return $app->make(LocalElasticsearchRepository::class);
                }
            );
        }
    }
}
