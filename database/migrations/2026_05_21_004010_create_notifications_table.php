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
        Schema::create('notifications', function (Blueprint $table) {

            $table->id();

            // quem recebe
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // quem fez a ação
            $table->foreignId('from_user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            // tipo
            $table->string('type');

            // mensagem
            $table->text('message');

            // link
            $table->string('url')->nullable();

            // lida ou não
            $table->boolean('read')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
