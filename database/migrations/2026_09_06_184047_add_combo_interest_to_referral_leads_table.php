<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_leads', function (Blueprint $table) {
            $table->string('combo_interest', 100)->nullable()->after('admin_remarks');
        });
    }

    public function down(): void
    {
        Schema::table('referral_leads', function (Blueprint $table) {
            $table->dropColumn('combo_interest');
        });
    }
};
