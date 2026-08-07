<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cp_material_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cp_id');
            $table->string('material_name');
            $table->decimal('quantity', 12, 2);
            $table->string('unit')->default('Piece');
            $table->decimal('rate', 12, 2);
            $table->decimal('total_amount', 14, 2);
            $table->date('entry_date');
            $table->string('invoice_number')->nullable();
            $table->string('invoice_file')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('cp_id')->references('id')->on('channel_partners')->onDelete('cascade');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cp_material_ledger');
    }
};
