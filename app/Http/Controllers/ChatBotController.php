<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatBotController extends Controller
{
    /**
     * Inicia uma nova conversa
     */
    public function startConversation(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        try {
            Log::info('Iniciando conversa para o usuário ID: ' . $user->id);

            $conversation = Conversation::create([
                'user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Conversa iniciada com sucesso.',
                'conversation_id' => $conversation->id
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erro ao iniciar conversa para o usuário ID: ' . $user->id . '. Erro: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao iniciar conversa.'], 500);
        }
    }

    /**
     * Envia uma mensagem e retorna a resposta do bot
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        $conversation = Conversation::where('id', $validated['conversation_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$conversation) {
            return response()->json(['error' => 'Conversa não encontrada ou não autorizada.'], 404);
        }

        // Verifica se a mensagem já foi enviada para evitar duplicação
        $existingMessage = Message::where('conversation_id', $conversation->id)
            ->where('user_message', $validated['message'])
            ->first();

        if ($existingMessage) {
            return response()->json([
                'message' => 'Mensagem já enviada anteriormente.',
                'user_message' => $existingMessage->user_message,
                'bot_response' => $existingMessage->bot_response ?? 'Resposta do bot já registrada separadamente.',
            ]);
        }

        // Registrar mensagem do usuário
        $userMessage = Message::create([
            'user_message' => $validated['message'],
            'bot_response' => null,
            'status' => 'received',
            'conversation_id' => $conversation->id,
        ]);

        // Gerar resposta do bot
        $botResponse = $this->generateBotResponse($validated['message']);

        // Registrar resposta do bot
        Message::create([
            'user_message' => null,
            'bot_response' => $botResponse,
            'status' => 'responded',
            'conversation_id' => $conversation->id,
        ]);

        // Encerrar conversa se o usuário disser "tchau"
        if (str_contains(strtolower($validated['message']), 'tchau')) {
            $conversation->update(['status' => 'closed']);
        }

        Log::info('Mensagem processada com sucesso na conversa ID: ' . $conversation->id);

        return response()->json([
            'message' => 'Mensagem enviada com sucesso.',
            'user_message_id' => $userMessage->id,
            'user_message' => $validated['message'],
            'bot_response' => $botResponse,
        ]);
    }

    /**
     * Gera a resposta automática do bot com base no conteúdo da mensagem
     */
    private function generateBotResponse($message)
    {
        $msg = strtolower(trim($message));

        $responses = [
            'olá' => 'Olá! Fala, FURIA! Como posso te ajudar a acompanhar o time hoje? 💛🖤',
            'oi' => 'Oi, torcedor da FURIA! Preparado para mais um jogo épico? 🎮🔥',
            'horário' => 'Nosso time está jogando das 15h às 18h hoje! Fique ligado para não perder nada! ⏰',
            'furia' => 'FURIA é paixão! Vai FURIA! Vamos arrasar nos campeonatos! 💥⚡️',
            'cs' => 'Você curte Counter-Strike? Qual jogador da FURIA é o seu favorito? 🎯',
            'tchau' => 'Tchau, FURIA fan! Estarei por aqui quando você precisar! 💬👋',
            'obrigado' => 'De nada, sempre à disposição! FURIA pra sempre! 💛🖤',
            'quem é o melhor jogador?' => 'O melhor jogador é a equipe toda! Mas, claro, o yuurih é um dos mais hypados. 😎',
            'fura' => 'O time da FURIA é imbatível, vamos com tudo! 🔥🔥',
        ];

        foreach ($responses as $keyword => $response) {
            if (str_contains($msg, $keyword)) {
                return $response;
            }
        }

        return 'Não entendi muito bem, mas sou FURIA, posso ajudar em mais alguma coisa? 😉';
    }
}
