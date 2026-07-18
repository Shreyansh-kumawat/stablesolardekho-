<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cashback_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referral_lead_id')->constrained('referral_leads')->onDelete('cascade');
            $table->decimal('deal_amount', 12, 2);
            $table->decimal('cashback_percentage', 5, 2);
            $table->decimal('cashback_amount', 10, 2);
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->string('payment_mode')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->text('admin_remarks')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashback_transactions');
    }
};
