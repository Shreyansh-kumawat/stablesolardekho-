<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        // Set all existing products to serial-off by default
        if (Schema::hasColumn('products', 'is_serialNumber_required')) {
            DB::table('products')->update(['is_serialNumber_required' => 0]);
        }

        // Change column default to false for future inserts
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_serialNumber_required')->default(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_serialNumber_required')->default(true)->change();
        });
    }
};
