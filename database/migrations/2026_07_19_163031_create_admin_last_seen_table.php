<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_last_seen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('section', 50);
            $table->timestamp('seen_at')->useCurrent();
            $table->unique(['user_id', 'section']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_last_seen');
    }
};
