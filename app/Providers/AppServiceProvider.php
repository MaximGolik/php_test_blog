<?php

namespace App\Providers;

use App\Cache\CustomRedisStore;
use App\Services\ArticleService;
use App\Services\ArticleServiceInterface;
use App\Services\CachedArticleService;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ArticleServiceInterface::class, function ($app) {
            $baseService = new ArticleService($app->get(EntityManagerInterface::class));
            return new CachedArticleService($baseService);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
