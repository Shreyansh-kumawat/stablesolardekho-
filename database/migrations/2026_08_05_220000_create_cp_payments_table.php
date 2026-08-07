<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cp_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cp_id')->constrained('channel_partners')->onDelete('cascade');
            $table->unsignedBigInteger('cp_order_id')->nullable();
            $table->foreign('cp_order_id')->references('id')->on('cp_orders')->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->string('payment_mode')->default('cash');
            $table->string('reference_number')->nullable();
            $table->string('screenshot')->nullable();
            $table->date('payment_date');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->text('remarks')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cp_payments');
    }
};
