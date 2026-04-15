<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('from_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_id')->constrained('users')->cascadeOnDelete();

            $table->text('message');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
