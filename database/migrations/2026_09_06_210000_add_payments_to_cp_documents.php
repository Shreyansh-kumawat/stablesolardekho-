<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cp_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('cp_documents', 'total_receivable')) {
                $table->decimal('total_receivable', 12, 2)->nullable()->after('remarks');
            }
        });

        if (!Schema::hasTable('cp_client_payments')) {
            Schema::create('cp_client_payments', function (Blueprint $table) {
                $table->id();
                $table->string('batch_id', 36)->index();
                $table->unsignedBigInteger('cp_id')->index();
                $table->decimal('amount', 12, 2);
                $table->date('payment_date');
                $table->string('remarks')->nullable();
                $table->unsignedBigInteger('added_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cp_client_payments');

        Schema::table('cp_documents', function (Blueprint $table) {
            if (Schema::hasColumn('cp_documents', 'total_receivable')) {
                $table->dropColumn('total_receivable');
            }
        });
    }
};
