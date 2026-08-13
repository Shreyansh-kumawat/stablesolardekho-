<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('solar_leads', 'selfie_image')) {
            Schema::table('solar_leads', function (Blueprint $table) {
                $table->string('selfie_image')->nullable()->after('city');
            });
        }
    }

    public function down(): void
    {
        Schema::table('solar_leads', function (Blueprint $table) {
            $table->dropColumn('selfie_image');
        });
    }
};
