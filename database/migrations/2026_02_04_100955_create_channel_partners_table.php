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
        Schema::create('channel_partners', function (Blueprint $table) {
            $table->id();
            $table->string('cp_name');
            $table->string('contact_person');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('full_address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->foreignId('cp_role')->constrained('channel_partner_roles');
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_partners');
    }
};
