<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('cp_payments', 'order_id_ref')) {
            Schema::table('cp_payments', function (Blueprint $table) {
                $table->string('order_id_ref', 100)->nullable()->after('cp_order_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('cp_payments', function (Blueprint $table) {
            $table->dropColumn('order_id_ref');
        });
    }
};
