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
    if (!Schema::hasColumn('skills', 'category')) {
        Schema::table('skills', function (Blueprint $table) {
            $table->string('category')->nullable();
        });
    }
}

public function down(): void
{
    if (Schema::hasColumn('skills', 'category')) {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
}
};
