<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_rfqs', function (Blueprint $table) {
            if (!Schema::hasColumn('customer_rfqs', 'matches')) {
                $table->json('matches')->nullable()->after('final_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('customer_rfqs', function (Blueprint $table) {
            if (Schema::hasColumn('customer_rfqs', 'matches')) {
                $table->dropColumn('matches');
            }
        });
    }
};
