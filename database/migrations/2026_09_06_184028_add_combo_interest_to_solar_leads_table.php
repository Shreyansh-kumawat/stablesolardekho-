<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solar_leads', function (Blueprint $table) {
            $table->string('combo_interest', 100)->nullable()->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::table('solar_leads', function (Blueprint $table) {
            $table->dropColumn('combo_interest');
        });
    }
};
