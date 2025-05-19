<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\AuthorRepositoryInterface;
use App\Repositories\BookRepositoryInterface;
use App\Repositories\EloquentAuthorRepository;
use App\Repositories\EloquentBookRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthorRepositoryInterface::class,
            EloquentAuthorRepository::class
        );

        $this->app->bind(
            BookRepositoryInterface::class,
            EloquentBookRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
