<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('cp_orders', 'admin_remarks')) {
            Schema::table('cp_orders', function (Blueprint $table) {
                $table->text('admin_remarks')->nullable()->after('order_notes');
            });
        }
    }

    public function down(): void
    {
        Schema::table('cp_orders', function (Blueprint $table) {
            $table->dropColumn('admin_remarks');
        });
    }
};
