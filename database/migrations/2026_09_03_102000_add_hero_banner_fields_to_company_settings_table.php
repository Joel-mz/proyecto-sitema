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
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('hero_image')->nullable()->after('logo');
            $table->string('hero_title')->nullable()->after('hero_image');
            $table->text('hero_subtitle')->nullable()->after('hero_title');
            $table->string('hero_badge')->nullable()->after('hero_subtitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['hero_image', 'hero_title', 'hero_subtitle', 'hero_badge']);
        });
    }
};
