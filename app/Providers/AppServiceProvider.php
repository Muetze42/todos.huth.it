<?php

namespace App\Providers;

use App\Services\LengthAwarePaginator as CustomLengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        $this->app->bind(
            LengthAwarePaginator::class,
            CustomLengthAwarePaginator::class
        );
    }
}
