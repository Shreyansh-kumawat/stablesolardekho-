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
        Schema::table("product_categories", function (Blueprint $table) {
            $table->string("slug")->nullable()->unique()->after("category_name");
        });

        foreach (DB::table("product_categories")->get() as $cat) {
            DB::table("product_categories")->where("id", $cat->id)->update([
                "slug" => Str::slug($cat->category_name),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table("product_categories", function (Blueprint $table) {
            $table->dropColumn("slug");
        });
    }
};
