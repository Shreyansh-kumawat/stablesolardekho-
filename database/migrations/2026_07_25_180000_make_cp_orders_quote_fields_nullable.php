<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE cp_orders MODIFY COLUMN quote_amount DECIMAL(10,2) NULL DEFAULT NULL");
            DB::statement("ALTER TABLE cp_orders MODIFY COLUMN quote_date DATE NULL DEFAULT NULL");
            DB::statement("ALTER TABLE cp_orders MODIFY COLUMN quote_validity_date DATE NULL DEFAULT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE cp_orders MODIFY COLUMN quote_amount DECIMAL(10,2) NOT NULL");
            DB::statement("ALTER TABLE cp_orders MODIFY COLUMN quote_date DATE NOT NULL");
            DB::statement("ALTER TABLE cp_orders MODIFY COLUMN quote_validity_date DATE NOT NULL");
        }
    }
};
