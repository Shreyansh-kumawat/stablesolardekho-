<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('serial_id')->nullable();
            $table->string('transaction_type');
            $table->integer('quantity');
            $table->string('transfer_type')->nullable();
            $table->unsignedBigInteger('transfer_to')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->unsignedBigInteger('performed_by');
            $table->string('txn_id')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('serial_id')->references('id')->on('product_serials')->nullOnDelete();
            $table->foreign('performed_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_inventory_transactions');
    }
};
