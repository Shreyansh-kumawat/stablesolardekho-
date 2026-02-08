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
        Schema::create('cp_product_inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cp_id')->constrained('channel_partners');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('serial_id')->nullable()->constrained('product_serials');
            $table->string('txn_id')->nullable();
            $table->integer('quantity');
            $table->string('transaction_type'); // e.g., 'addition', 'removal', 'adjustment'
            $table->integer('txn_done_from')->nullable()->comment('CP id of vendor, supplier, user from purchase or sale');
            $table->foreignId('performed_by')->constrained('users');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('invoice_number')->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cp_product_inventory_transactions');
    }
};
