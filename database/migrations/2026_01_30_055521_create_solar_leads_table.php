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
        Schema::create('solar_leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_id')->unique();
            $table->string(column: 'lead_by')->nullable()->comment('user who created the lead');
            $table->string('customer_name');
            $table->string('mobile_number')->nullable();
            $table->string('email_id')->nullable();
            $table->string('monthly_bill')->nullable();
            $table->string('connection_type');      
            $table->string('pin_code')->nullable();
            $table->string('city')->nullable();
            $table->string('complete_address')->nullable();
            $table->string('state_name')->nullable();            
            $table->string('proposed_capacity')->nullable()->comment('in KW');
            $table->tinyInteger('lead_status')->default('0');
            $table->text('remarks')->nullable();           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solar_leads');
    }
};
