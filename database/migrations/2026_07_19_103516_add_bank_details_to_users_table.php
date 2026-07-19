<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bank_account_number', 30)->nullable()->after('pincode');
            $table->string('bank_ifsc', 20)->nullable()->after('bank_account_number');
            $table->string('bank_name', 100)->nullable()->after('bank_ifsc');
            $table->string('bank_account_holder', 100)->nullable()->after('bank_name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bank_account_number', 'bank_ifsc', 'bank_name', 'bank_account_holder']);
        });
    }
};
