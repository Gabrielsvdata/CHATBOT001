<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    /**
     * Lista todas as conversas do usuário autenticado.
     */
    public function index()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        try {
            $conversations = Conversation::where('user_id', $user->id)->get();

            if ($conversations->isEmpty()) {
                return response()->json(['message' => 'Nenhuma conversa encontrada.'], 404);
            }

            return response()->json($conversations);
        } catch (\Exception $e) {
            Log::error("Erro ao listar conversas do usuário ID {$user->id}: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao buscar conversas.'], 500);
        }
    }

    /**
     * Cria uma nova conversa para o usuário autenticado.
     */
    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        try {
            Log::info("Criando conversa para o usuário ID: {$user->id}");

            $conversation = Conversation::create([
                'user_id' => $user->id,
                'started_at' => now(),
            ]);

            return response()->json($conversation, 201);
        } catch (\Exception $e) {
            Log::error("Erro ao criar conversa para o usuário ID {$user->id}: " . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar a conversa.'], 500);
        }
    }
}
