<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    /**
     * Tabela associada ao modelo.
     * (opcional se estiver seguindo a convenção padrão do Laravel)
     */
    protected $table = 'messages';

    /**
     * Campos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'user_message',
        'bot_response',
        'status',
        'conversation_id',
    ];

    /**
     * Relacionamento: a mensagem pertence a uma conversa.
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
