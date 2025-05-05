<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConversationController;

// Teste de rota simples (para verificar se a API está funcionando)
Route::get('/ping', function () {
    return response()->json(['message' => 'API OK']);
});

// ======================================================
// Rotas públicas (sem autenticação necessária)
// ======================================================

// Registro de usuário
Route::post('/register', [AuthController::class, 'register']);

// Login de usuário (gera token para autenticação)
Route::post('/login', [AuthController::class, 'login']);

// ======================================================
// Rotas protegidas (exigem autenticação via token Sanctum)
// ======================================================
Route::middleware('auth:sanctum')->group(function () {

    // ================================================== 
    // Rotas do ChatBot
    // ==================================================
    Route::post('/start-conversation', [ChatBotController::class, 'startConversation']);
    Route::post('/send-message', [ChatBotController::class, 'sendMessage']);

    // ==================================================
    // Rotas de Conversas
    // ==================================================
    Route::get('/conversations', [ConversationController::class, 'index'])
        ->middleware('throttle:60,1');

    Route::post('/conversations', [ConversationController::class, 'store'])
        ->middleware('throttle:10,1');

    // ==================================================
    // Logout (revoga todos os tokens do usuário logado)
    // ==================================================
    Route::post('/logout', [AuthController::class, 'logout']);
});
