<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE cp_orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'completed', 'shipped', 'delivered', 'cancelled', 'rejected') DEFAULT 'pending'");
        }

        Schema::table('cp_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('cp_orders', 'payment_status')) {
                $table->string('payment_status')->default('verification_pending')->after('status');
            }
            if (!Schema::hasColumn('cp_orders', 'payment_screenshot')) {
                $table->string('payment_screenshot')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('cp_orders', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_screenshot');
            }
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE cp_orders MODIFY COLUMN status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending'");
        }
    }
};
