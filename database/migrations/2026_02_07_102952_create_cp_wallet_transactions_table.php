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
        Schema::create('cp_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cp_id');
            $table->string('transaction_type'); // 'credit' or 'debit'
            $table->decimal('amount', 15, 2);
            $table->decimal('opening_balance', 15, 2);
            $table->decimal('closing_balance', 15, 2);
            $table->string('source')->nullable()->comment('e.g., admin transfer, rebate payout, FUND_REQUEST.');
            $table->string('txn_id')->unique()->nullable()->comment('Unique transaction ID for reference');
            $table->string('status')->nullable()->comment('SUCCESS, PENDING, FAILED');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('txn_done_by')->nullable();
            $table->foreign('txn_done_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cp_wallet_transactions');
    }
};
