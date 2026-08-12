<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cp_documents', function (Blueprint $table) {
            $table->string('client_name')->nullable()->after('cp_id');
            $table->string('client_phone', 20)->nullable()->after('client_name');
            $table->string('client_address')->nullable()->after('client_phone');
            $table->string('batch_id', 36)->nullable()->after('client_address');
        });
    }

    public function down(): void
    {
        Schema::table('cp_documents', function (Blueprint $table) {
            $table->dropColumn(['client_name', 'client_phone', 'client_address', 'batch_id']);
        });
    }
};
