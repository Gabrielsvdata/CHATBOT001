<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;

// Teste de rota simples (para verificar se a API está funcionando)
Route::get('/ping', function () {
    return response()->json(['message' => 'API OK']);
});

// Chatbot
Route::post('/start-conversation', [ChatBotController::class, 'startConversation']);
Route::post('/send-message', [ChatBotController::class, 'sendMessage']);

// Autenticação
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Conversas
Route::get('/conversations', [ConversationController::class, 'index']);
Route::post('/conversations', [ConversationController::class, 'store']);

// Logout (requisição POST para realizar o logout)
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    // Revogar todos os tokens do usuário atual
    $request->user()->tokens->delete();

    // Resposta de sucesso em JSON
    return response()->json(['message' => 'Logout efetuado com sucesso!']);
});


/*GET http://127.0.0.1:8000/api/ping

POST http://127.0.0.1:8000/api/start-conversation

POST http://127.0.0.1:8000/api/send-message

POST http://127.0.0.1:8000/api/register

POST http://127.0.0.1:8000/api/login

GET http://127.0.0.1:8000/api/conversations

POST http://127.0.0.1:8000/api/conversations
// 
POST http://127.0.0.1:8000/api/logout*/