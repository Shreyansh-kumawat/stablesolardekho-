<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE customer_orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'verification_pending') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE customer_orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed') DEFAULT 'pending'");
    }
};
