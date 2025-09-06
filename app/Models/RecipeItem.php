<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecipeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'component_type',
        'component_id',
        'qty',
        'unit',
    ];

    /*
    |--------------------------------------------------------------------------
    | İlişkiler
    |--------------------------------------------------------------------------
    */

    public function component()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
