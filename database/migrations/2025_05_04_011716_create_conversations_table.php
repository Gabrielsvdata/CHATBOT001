<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id(); // Criar coluna 'id'
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Chave estrangeira para 'user_id', permite nulo e seta null se o usuário for excluído
            $table->timestamps(); // Criar colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
