<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PayWithQr extends Model
{
    protected $table = 'pay_with_qr';

    protected $fillable = [
        'toplam_tutar',
        'indirim_oranı',
    ];

    protected function casts(): array
    {
        return [
            'toplam_tutar' => 'decimal:2',
            'indirim_oranı' => 'decimal:2',
        ];
    }

    protected function netTutar(): Attribute
    {
        return Attribute::get(function (): float {
            $brut = (float) $this->toplam_tutar;
            $oran = (float) $this->indirim_oranı;

            return round($brut * (1 - ($oran / 100)), 2);
        });
    }
}
