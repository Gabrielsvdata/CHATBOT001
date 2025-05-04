<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'started_at',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    // Relacionamento: uma conversa pertence a um usuário (opcional)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento: uma conversa tem muitas mensagens
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
