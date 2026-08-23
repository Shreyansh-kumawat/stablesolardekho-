<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_rfqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 15);
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->text('item_description');
            $table->unsignedInteger('quantity')->default(1);
            $table->string('preferred_brand')->nullable();
            $table->text('additional_notes')->nullable();
            $table->enum('status', ['pending', 'processing', 'quoted', 'accepted', 'rejected', 'closed'])->default('pending');
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->text('admin_remarks')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('quoted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_rfqs');
    }
};
