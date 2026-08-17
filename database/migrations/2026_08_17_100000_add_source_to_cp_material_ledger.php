<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cp_material_ledger', function (Blueprint $table) {
            $table->string('source', 50)->default('manual')->after('remarks');
        });
    }

    public function down(): void
    {
        Schema::table('cp_material_ledger', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
