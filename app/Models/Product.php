<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\Conversions\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;


    protected $fillable = [
        'name',
        'total_grams',
        'cost',
        'type',
        'image',
    ];

    public function recipeItems()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function rawMaterials()
    {
        return $this->hasManyThrough(
            RawMaterial::class,
            RecipeItem::class,
            'product_id',
            'id',
            'id',
            'component_id'
        )->where('component_type', RawMaterial::class);
    }

    public function subProducts()
    {
        return $this->hasManyThrough(
            Product::class,
            RecipeItem::class,
            'product_id',
            'id',
            'id',
            'component_id'
        )->where('component_type', Product::class);
    }

    public function recalculateCost(): void
    {
        $ingredients = $this->recipeItems()->get()->map(fn($item) => [
            'type' => $item->component_type === RawMaterial::class ? 'raw' : 'product',
            'item_id' => $item->component_id,
            'amount' => $item->qty,
            'unit' => $item->unit,
        ])->toArray();

        $this->total_grams = \App\Services\ProductService::calculateTotalGrams($ingredients);
        $this->cost = \App\Services\ProductService::calculateCost($ingredients);
        $this->saveQuietly();

        // Final ürün ise parent ürünleri güncelle
        \App\Services\ProductService::updateParentProducts($this->id);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products')->singleFile();
    }
    
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')
             ->width(300)
             ->height(300)
             ->fit(Fit::Contain) // küçük harf: contain
             ->format('webp') // WebP dönüşümü
             ->nonQueued();
    }
    
}
