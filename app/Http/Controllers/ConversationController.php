<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    // Método para listar todas as conversas
    public function index()
    {
        try {
            // Obtém todas as conversas
            $conversations = Conversation::all();
            return response()->json($conversations);
        } catch (\Exception $e) {
            // Loga o erro
            Log::error('Erro ao listar conversas: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar conversas.'], 500);
        }
    }

    // Método para criar uma nova conversa
    public function store(Request $request)
    {
        // Valida a entrada do request
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id', // Verifica se o user_id existe na tabela users
        ]);

        try {
            // Cria a conversa no banco de dados
            $conversation = Conversation::create([
                'user_id' => $validated['user_id'] ?? null, // Atribui user_id se válido
            ]);

            // Retorna a conversa criada com código de status 201
            return response()->json($conversation, 201);
        } catch (\Exception $e) {
            // Loga o erro
            Log::error('Erro ao criar conversa: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar a conversa.'], 500);
        }
    }
}
