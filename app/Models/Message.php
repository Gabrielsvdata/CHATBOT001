<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'chat_messages';  // Nome da nova tabela

    protected $fillable = [
        'user_message',
        'bot_response',
        'status',
        'conversation_id',
    ];
    // Relacionamento com a conversa
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
