<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('product_inventory_transactions', 'supplier_name')) {
            Schema::table('product_inventory_transactions', function (Blueprint $table) {
                $table->string('supplier_name')->nullable()->after('txn_done_from');
            });
        }
    }

    public function down(): void
    {
        Schema::table('product_inventory_transactions', function (Blueprint $table) {
            $table->dropColumn('supplier_name');
        });
    }
};
