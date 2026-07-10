<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->nullable()->after('description');
            $table->string('brand')->nullable()->after('type');
            $table->string('model')->nullable()->after('brand');
            $table->string('operating_voltage')->nullable()->after('model');
            $table->string('solar_panel_type')->nullable()->after('operating_voltage');
            $table->string('mnre_approved')->nullable()->after('solar_panel_type');
            $table->string('certifications')->nullable()->after('mnre_approved');
            $table->string('manufacturer_warranty')->nullable()->after('certifications');
            $table->string('number_of_cells')->nullable()->after('manufacturer_warranty');
            $table->string('encapsulate')->nullable()->after('number_of_cells');
            $table->string('country_of_origin')->nullable()->after('encapsulate');
            $table->string('input_voltage')->nullable()->after('country_of_origin');
            $table->string('max_supported_panel_power')->nullable()->after('input_voltage');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'brand', 'model', 'operating_voltage', 'solar_panel_type',
                'mnre_approved', 'certifications', 'manufacturer_warranty',
                'number_of_cells', 'encapsulate', 'country_of_origin',
                'input_voltage', 'max_supported_panel_power',
            ]);
        });
    }
};
