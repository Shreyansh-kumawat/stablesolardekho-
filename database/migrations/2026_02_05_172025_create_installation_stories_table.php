<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installation_stories', function (Blueprint $table) {
            $table->id();
            $table->string('installation_type')->nullable()->comment('e.g., Residential, Commercial');
            $table->string('location')->nullable();
            $table->integer('system_size_kw')->nullable();
            $table->date('installation_date')->nullable();
            $table->boolean('active_status')->default(true);
            $table->json('photos')->nullable()->comment('Array of photo URLs');
            $table->string('videos')->nullable()->comment('Array of video URLs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installation_stories');
    }
};
