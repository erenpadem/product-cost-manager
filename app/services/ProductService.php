<?php

namespace App\Services;

use App\Models\Product;
use App\Models\RawMaterial;
use App\Models\RecipeItem;

class ProductService
{
    public static function convertToGrams(float $amount, string $unit): float
    {
        return match ($unit) {
            'kg' => $amount * 1000,
            'g' => $amount,
            'l' => $amount * 1000,
            'ml' => $amount,
            'kw' => $amount,
            default => $amount,
        };
    }

    public static function calculateTotalGrams(array $ingredients): float
    {
        $total = 0;
        foreach ($ingredients as $ingredient) {
            $total += self::convertToGrams($ingredient['amount'] ?? 0, $ingredient['unit'] ?? 'g');
        }
        return $total;
    }

    public static function calculateCost(array $ingredients): float
    {
        $totalCost = 0;
        foreach ($ingredients as $ingredient) {
            $type = $ingredient['type'] ?? null;
            $itemId = $ingredient['item_id'] ?? null;
            $amount = (float)($ingredient['amount'] ?? 0);
            $unit = $ingredient['unit'] ?? 'g';

            if (!$type || !$itemId || $amount <= 0) continue;

            $amountInGrams = self::convertToGrams($amount, $unit);
            $costPerGram = 0;

            if ($type === 'raw') {
                $raw = RawMaterial::find($itemId);
                if ($raw) $costPerGram = $raw->price_per_kg / 1000;
            } elseif ($type === 'product') {
                $product = Product::find($itemId);
                if ($product && $product->total_grams > 0) {
                    $product->refresh(); // Güncel maliyeti al
                    $costPerGram = $product->cost / $product->total_grams;
                }
            }

            $totalCost += $amountInGrams * $costPerGram;
        }

        return $totalCost;
    }

    public static function syncIngredients(Product $product, array $ingredients): void
    {
        $product->recipeItems()->delete();

        foreach ($ingredients as $item) {
            if ($item['type'] === 'product' && $item['item_id'] == $product->id) continue;

            $product->recipeItems()->create([
                'component_type' => $item['type'] === 'raw' ? RawMaterial::class : Product::class,
                'component_id' => $item['item_id'],
                'qty' => $item['amount'] ?? 0,
                'unit' => $item['unit'] ?? 'g',
            ]);
        }

        $product->recalculateCost();
    }

    public static function recalculateProductById(int $productId): void
    {
        $product = Product::find($productId);
        if ($product) $product->recalculateCost();
    }

    public static function updateParentProducts(int $productId): void
    {
        $parentIds = RecipeItem::where('component_type', Product::class)
            ->where('component_id', $productId)
            ->pluck('product_id')
            ->unique();

        foreach ($parentIds as $parentId) {
            self::recalculateProductById($parentId);
        }
    }
}
