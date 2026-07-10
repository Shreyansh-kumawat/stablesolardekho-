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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('item_code')->unique();
            $table->decimal('current_sale_price', 10, 2)->nullable();
            $table->string('uom');
            $table->foreignId('category_id')->constrained('product_categories');
            $table->foreignId('sub_category_id')->constrained('product_sub_categories');
            $table->boolean('is_serialNumber_required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
