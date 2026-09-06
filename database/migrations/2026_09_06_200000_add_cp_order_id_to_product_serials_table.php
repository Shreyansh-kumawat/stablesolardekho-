<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_serials', function (Blueprint $table) {
            if (!Schema::hasColumn('product_serials', 'cp_order_id')) {
                $table->unsignedBigInteger('cp_order_id')->nullable()->after('customer_order_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_serials', function (Blueprint $table) {
            if (Schema::hasColumn('product_serials', 'cp_order_id')) {
                $table->dropColumn('cp_order_id');
            }
        });
    }
};
