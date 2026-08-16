<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kit_items', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('product_id');
            $table->unsignedBigInteger('component_product_id')->nullable()->after('category_id');
            $table->unsignedInteger('quantity')->default(1)->after('quantity_label');
        });
    }

    public function down(): void
    {
        Schema::table('kit_items', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'component_product_id', 'quantity']);
        });
    }
};
