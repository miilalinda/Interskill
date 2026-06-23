<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('highlight_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('highlight_id')->constrained()->onDelete('cascade');
            $table->string('caminho');
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('highlight_items');
    }
};
