<?php

namespace App\Observers;

use App\Models\RawMaterial;
use App\Services\ProductService;

class RawMaterialObserver
{
    public function saved(RawMaterial $raw)
    {
        $raw->recipeItems->pluck('product_id')->unique()->each(function($productId) {
            ProductService::recalculateProductById($productId);
        });
    }
}
