<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // 'default' = normal category, 'color' = color-swatch filter, 'size' = size-button filter
            $table->string('type', 20)->default('default')->after('order');
            // Hex or RGB color code stored on child categories of a color-type parent
            $table->string('color_code', 30)->nullable()->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['type', 'color_code']);
        });
    }
};
