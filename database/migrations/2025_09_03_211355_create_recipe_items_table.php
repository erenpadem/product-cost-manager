<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipe_items', function (Blueprint $table) {
            $table->id();

            // Hangi ürünün tarifine ait
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Malzeme veya alt ürün: morph
            $table->nullableMorphs('component'); 
            // component_type = App\Models\RawMaterial veya App\Models\Product
            // component_id   = ilgili id
            
            $table->decimal('qty', 10, 2);   // miktar
            $table->enum('unit', ['kg', 'g', 'ml', 'l', 'adet', 'kwh'])->default('g'); // birim
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_items');
    }
};
