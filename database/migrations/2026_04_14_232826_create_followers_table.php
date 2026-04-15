<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();

            // Usuário que está sendo seguido
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Usuário que está seguindo
            $table->foreignId('follower_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();

            // Evita duplicar seguidores
            $table->unique(['user_id', 'follower_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('followers');
    }
};
