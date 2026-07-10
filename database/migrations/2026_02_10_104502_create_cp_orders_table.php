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
        Schema::create('cp_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cp_id');
            $table->string('order_id')->unique();
            $table->json('products');
            $table->text('order_notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->tinyInteger('inQuoteSent')->default(0);
            $table->decimal('quote_amount', 10, 2);
            $table->date('order_date');
            $table->date('quote_date');
            $table->date('quote_validity_date');
            $table->text('admin_remarks')->nullable();
            $table->foreign('cp_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger(column: 'quote_generated_by')->nullable();
            $table->foreign('quote_generated_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cp_orders');
    }
};
