<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RawMaterial extends Model
{
    use HasFactory;
    use LogsActivity;

    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // tüm fillable alanları loglar
            ->useLogName('raw_material') // log_name sütununa düşer
            ->setDescriptionForEvent(fn (string $eventName) => "Ham madde {$eventName}");
    }
    protected $fillable = [
        'name',
        'price',
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
            'kg' => ($this->price / 1000) * $amount,
            'g' => $this->price * $amount,
            'l' => ($this->price / 1000) * $amount,
            'ml' => $this->price * $amount,
            'kw' => $this->price * $amount,
            default => 0,
        };
    }
}
