<?php

namespace App\Providers;

use App\Jobs\SendWebNotification;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        Relation::morphMap([
            'comment' => config('arbitrage.models_map.posts.comments.comment'),
            'post' => config('arbitrage.models_map.posts.post'),
            'tag' => config('arbitrage.models_map.tags.tag'),
        ]);

        $this->app->bindMethod(SendWebNotification::class.'@handle', function ($job, $app) {
            return $job->handle($app->make(\App\Data\Providers\Arbitrage\Stream\StreamProvider::class));
        });

        $this->app->bindMethod(\App\Jobs\CreateUserWebNotification::class.'@handle', function ($job, $app) {
            return $job->handle($app->make(\App\Data\Providers\Arbitrage\Gateway\GatewayProvider::class));
        });
    }
}
