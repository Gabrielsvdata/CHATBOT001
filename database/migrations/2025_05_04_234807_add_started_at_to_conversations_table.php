<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable(); // Adicionando a coluna 'started_at'
        });
    }
    
    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn('started_at'); // Remover a coluna caso precise reverter a migração
        });
    }
};
