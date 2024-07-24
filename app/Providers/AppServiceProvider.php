<?php

namespace App\Providers;

use App\Domain\Repositories\AirportRepository;
use App\Domain\Repositories\AirportRepositoryInterface;
use App\Domain\Services\AirportService;
use App\Domain\Services\AirportServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AirportRepositoryInterface::class, AirportRepository::class);
        $this->app->bind(AirportServiceInterface::class, AirportService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
