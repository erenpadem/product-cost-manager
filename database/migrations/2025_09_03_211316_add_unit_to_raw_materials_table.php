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
        Schema::table('raw_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('raw_materials', 'unit')) {
                $table->string('unit')->default('kg')
                      ->check("unit IN ('kg', 'g', 'ml', 'lt', 'adet', 'kwh')");
            }
        });
        
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            //
        });
    }
};
