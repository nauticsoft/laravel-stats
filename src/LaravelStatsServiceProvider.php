<?php

namespace Nauticsoft\LaravelStats;

use Illuminate\Support\ServiceProvider;

class LaravelStatsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishesMigrations([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);
    }
}
