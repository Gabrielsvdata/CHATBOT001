<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    /**
     * Inicia uma nova conversa
     */
    public function startConversation(Request $request)
    {
        $conversation = Conversation::create([
            'user_id' => auth()->id(),
            'started_at' => now(),
        ]);

        return response()->json([
            'message' => 'Conversa iniciada com sucesso.',
            'conversation_id' => $conversation->id
        ], 201);
    }

    /**
     * Envia uma mensagem e retorna resposta do bot
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $conversation = Conversation::find($validated['conversation_id']);

        if (!$conversation) {
            return response()->json(['error' => 'Conversa não encontrada.'], 404);
        }

        // Armazena mensagem do usuário
        $userMessage = Message::create([
            'user_message' => $validated['message'],
            'status' => 'received',
            'conversation_id' => $conversation->id,
        ]);

        // Gera e armazena resposta do bot
        $botResponse = $this->generateBotResponse($validated['message']);

        $botMessage = Message::create([
            'user_message' => $validated['message'],
            'bot_response' => $botResponse,
            'status' => 'responded',
            'conversation_id' => $conversation->id,
        ]);

        // Encerra a conversa, se necessário
        if (str_contains(strtolower($validated['message']), 'tchau')) {
            $conversation->update(['status' => 'closed']);
        }

        return response()->json([
            'message' => 'Mensagem enviada com sucesso.',
            'user_message_id' => $userMessage->id,
            'bot_response' => $botResponse
        ], 200);
    }

    /**
     * Gera uma resposta do bot com base na mensagem do usuário
     */
    private function generateBotResponse($message)
    {
        $msg = strtolower($message);

        $responses = [
            'olá' => 'Olá! Tudo bem? Como posso te ajudar?',
            'oi' => 'Oi! Estou aqui para conversar com você.',
            'horário' => 'Nosso atendimento é das 9h às 18h, de segunda a sexta.',
            'furia' => 'Vai FURIA! Um dos maiores times de eSports do Brasil!',
            'cs' => 'Você gosta de Counter-Strike? Qual seu jogador favorito?',
            'tchau' => 'Até mais! Estarei por aqui se precisar de mim.',
            'obrigado' => 'De nada! Estou sempre à disposição.',
        ];

        foreach ($responses as $keyword => $response) {
            if (str_contains($msg, $keyword)) {
                return $response;
            }
        }

        return 'Hmm... não entendi muito bem. Pode tentar reformular sua pergunta?';
    }
}
