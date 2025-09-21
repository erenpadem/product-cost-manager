<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Order extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = ['user_id','status','delivery_date','note','invoice_path'];
    public function items() { return $this->hasMany(OrderItem::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('invoices')
            ->singleFile();
    }
}