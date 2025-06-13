<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('color', 7)->nullable()->after('description'); // HEX color
            $table->boolean('is_active')->default(true)->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn(['description', 'color', 'is_active']);
        });
    }
};
