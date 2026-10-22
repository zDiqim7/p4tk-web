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
        Schema::table('products', function (Blueprint $table) {
            $table->string('catalog_type', 20)->default('menu')->index();
            $table->boolean('is_featured')->default(false);
            $table->json('catalog_attributes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['catalog_type']);
            $table->dropColumn(['catalog_type', 'is_featured', 'catalog_attributes']);
        });
    }
};
