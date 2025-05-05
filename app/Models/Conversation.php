<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    /**
     * Campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'user_id',      // ID do usuário (chave estrangeira)
        'started_at',   // Data de início da conversa
        'status',       // Status da conversa (ex: 'active', 'closed', etc.)
    ];

    /**
     * Cast para garantir que o campo started_at seja tratado como uma data.
     */
    protected $casts = [
        'started_at' => 'datetime',
    ];

    /**
     * Relacionamento: uma conversa pertence a um usuário (um-para-muitos).
     */
    public function user()
    {
        return $this->belongsTo(User::class);  // Um usuário pode ter várias conversas
    }

    /**
     * Relacionamento: uma conversa tem muitas mensagens (um-para-muitos).
     */
    public function messages()
    {
        return $this->hasMany(Message::class);  // Uma conversa pode ter várias mensagens
    }

    /**
     * Se não for utilizar os timestamps padrão (created_at e updated_at),
     * você pode desabilitar com:
     * public $timestamps = false;
     */
    public $timestamps = true;

    // Exemplo de método customizado: pode adicionar lógica como status de conversas ativas ou encerradas.
    public function activeConversations()
    {
        return $this->where('status', 'active');
    }
}
