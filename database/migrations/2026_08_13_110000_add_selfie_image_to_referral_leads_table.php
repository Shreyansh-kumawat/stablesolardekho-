<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('referral_leads', 'selfie_image')) {
            Schema::table('referral_leads', function (Blueprint $table) {
                $table->string('selfie_image')->nullable()->after('monthly_bill');
            });
        }
    }

    public function down(): void
    {
        Schema::table('referral_leads', function (Blueprint $table) {
            $table->dropColumn('selfie_image');
        });
    }
};
