<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\RawMaterial;
use App\Observers\ProductObserver;
use App\Observers\RawMaterialObserver;
use Filament\Facades\Filament;
use Filament\Panel;
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
        RawMaterial::observe(RawMaterialObserver::class);
        Product::observe(ProductObserver::class);
        Filament::serving(function () {
            Filament::registerPanel(function (Panel $panel) {
                $panel
                    ->id('admin')
                    ->path('/admin')
                    ->default(); // ⚡ Default panel
            });
        });
    }
}
