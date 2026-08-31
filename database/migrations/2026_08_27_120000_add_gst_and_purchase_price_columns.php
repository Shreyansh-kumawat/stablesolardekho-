<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_inventory_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('product_inventory_transactions', 'gst_percent')) {
                $table->decimal('gst_percent', 5, 2)->nullable()->after('unit_price');
            }
            if (!Schema::hasColumn('product_inventory_transactions', 'gst_amount')) {
                $table->decimal('gst_amount', 12, 2)->nullable()->after('gst_percent');
            }
            if (!Schema::hasColumn('product_inventory_transactions', 'total_with_gst')) {
                $table->decimal('total_with_gst', 12, 2)->nullable()->after('gst_amount');
            }
        });

        Schema::table('warehouse_inventory_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('warehouse_inventory_transactions', 'gst_percent')) {
                $table->decimal('gst_percent', 5, 2)->nullable()->after('unit_price');
            }
            if (!Schema::hasColumn('warehouse_inventory_transactions', 'gst_amount')) {
                $table->decimal('gst_amount', 12, 2)->nullable()->after('gst_percent');
            }
            if (!Schema::hasColumn('warehouse_inventory_transactions', 'total_with_gst')) {
                $table->decimal('total_with_gst', 12, 2)->nullable()->after('gst_amount');
            }
        });

        Schema::table('product_serials', function (Blueprint $table) {
            if (!Schema::hasColumn('product_serials', 'gst_percent')) {
                $table->decimal('gst_percent', 5, 2)->nullable()->after('purchase_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_inventory_transactions', function (Blueprint $table) {
            foreach (['gst_percent', 'gst_amount', 'total_with_gst'] as $c) {
                if (Schema::hasColumn('product_inventory_transactions', $c)) $table->dropColumn($c);
            }
        });
        Schema::table('warehouse_inventory_transactions', function (Blueprint $table) {
            foreach (['gst_percent', 'gst_amount', 'total_with_gst'] as $c) {
                if (Schema::hasColumn('warehouse_inventory_transactions', $c)) $table->dropColumn($c);
            }
        });
        Schema::table('product_serials', function (Blueprint $table) {
            if (Schema::hasColumn('product_serials', 'gst_percent')) $table->dropColumn('gst_percent');
        });
    }
};
