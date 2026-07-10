<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug', 8)->nullable()->unique()->after('item_code');
        });

        // Backfill slugs for existing products
        $products = DB::table('products')->whereNull('slug')->get();
        foreach ($products as $product) {
            do {
                $slug = strtoupper(Str::random(3)) . rand(100, 999);
            } while (DB::table('products')->where('slug', $slug)->exists());

            DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
