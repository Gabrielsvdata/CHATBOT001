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
        // Verifique se a coluna já existe antes de tentar adicioná-la
        if (!Schema::hasColumn('conversations', 'user_id')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            });
        }

        // Adicionar a coluna 'status' se não existir
        if (!Schema::hasColumn('conversations', 'status')) {
            Schema::table('conversations', function (Blueprint $table) {
                $table->enum('status', ['open', 'closed'])->default('open');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['user_id', 'status']);
        });
    }
};
