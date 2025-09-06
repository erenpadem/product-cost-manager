<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductService;

class ProductObserver
{
    /**
     * Ürün güncellenince, bu ürünü kullanan üst ürünlerin maliyetini güncelle
     */
    public function updated(Product $product): void
    {
        ProductService::updateParentProducts($product->id);
    }
}
