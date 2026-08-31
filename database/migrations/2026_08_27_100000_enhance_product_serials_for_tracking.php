<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_serials', function (Blueprint $table) {
            if (!Schema::hasColumn('product_serials', 'warehouse_id')) {
                $table->unsignedBigInteger('warehouse_id')->nullable()->after('current_location');
            }
            if (!Schema::hasColumn('product_serials', 'customer_order_id')) {
                $table->unsignedBigInteger('customer_order_id')->nullable()->after('warehouse_id');
            }
            if (!Schema::hasColumn('product_serials', 'batch_txn_id')) {
                $table->string('batch_txn_id')->nullable()->index()->after('customer_order_id');
            }
            if (!Schema::hasColumn('product_serials', 'purchase_price')) {
                $table->decimal('purchase_price', 12, 2)->nullable()->after('batch_txn_id');
            }
            if (!Schema::hasColumn('product_serials', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('purchase_price');
            }
            if (!Schema::hasColumn('product_serials', 'invoice_date')) {
                $table->date('invoice_date')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('product_serials', 'supplier_name')) {
                $table->string('supplier_name')->nullable()->after('invoice_date');
            }
        });

        Schema::table('product_serials', function (Blueprint $table) {
            $table->index(['product_id', 'status']);
            $table->index('serial_number');
        });
    }

    public function down(): void
    {
        Schema::table('product_serials', function (Blueprint $table) {
            foreach (['warehouse_id','customer_order_id','batch_txn_id','purchase_price','invoice_number','invoice_date','supplier_name'] as $col) {
                if (Schema::hasColumn('product_serials', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
