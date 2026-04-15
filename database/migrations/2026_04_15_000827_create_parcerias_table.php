<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parcerias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // quem recebe
            $table->foreignId('solicitante_id')->constrained('users')->cascadeOnDelete(); // quem envia

            $table->enum('status', ['pendente', 'aceito', 'recusado'])->default('pendente');

            $table->timestamps();

            $table->unique(['user_id', 'solicitante_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('parcerias');
    }
};
