<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('solar_teams', function (Blueprint $table) {
            if (!Schema::hasColumn('solar_teams', 'sort_order')) {
                $table->integer('sort_order')->nullable()->after('status');
            }
            if (!Schema::hasColumn('solar_teams', 'is_pinned')) {
                $table->boolean('is_pinned')->default(false)->after('sort_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('solar_teams', function (Blueprint $table) {
            if (Schema::hasColumn('solar_teams', 'sort_order')) $table->dropColumn('sort_order');
            if (Schema::hasColumn('solar_teams', 'is_pinned')) $table->dropColumn('is_pinned');
        });
    }
};
