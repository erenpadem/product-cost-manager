<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RawMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_per_kg',
        'unit', // kg, g, ml, l, kw
    ];

    public function recipeItems()
    {
        return $this->morphMany(RecipeItem::class, 'component');
    }

    public function recalculateProducts(): void
    {
        $this->recipeItems->each(function ($item) {
            $item->product?->recalculateCostRecursive();
        });
    }

    public function calculateCost(float $amount): float
    {
        return match ($this->unit) {
            'kg' => ($this->price_per_kg / 1000) * $amount,
            'g' => $this->price_per_kg * $amount,
            'l' => ($this->price_per_kg / 1000) * $amount,
            'ml' => $this->price_per_kg * $amount,
            'kw' => $this->price_per_kg * $amount,
            default => 0,
        };
    }
}
