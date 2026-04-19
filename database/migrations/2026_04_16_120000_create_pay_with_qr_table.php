<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pay_with_qr', function (Blueprint $table) {
            $table->id();
            $table->decimal('toplam_tutar', 15, 2);
            $table->decimal('indirim_oranı', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pay_with_qr');
    }
};
