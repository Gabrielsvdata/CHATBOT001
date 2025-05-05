<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Registrar um novo usuário
     */
    public function register(Request $request)
    {
        // Validação mais explícita com mensagens personalizadas (opcional)
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => '',  
        ], [
            'password.confirmed' => 'A confirmação da senha não coincide.',
        ]);

        if ($validator->fails()) {
            // Retorna erros de validação com status 422
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Verifica se o email já está em uso
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['error' => 'Este e-mail já está registrado.'], 409);
        }

        // Criação do usuário
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Criação do token de autenticação
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Usuário registrado com sucesso!',
                'token'   => $token,
                'user'    => $user,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao registrar o usuário: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao registrar o usuário. Tente novamente mais tarde.'], 500);
        }
    }

    /**
     * Realizar o login do usuário
     */
    public function login(Request $request)
    {
        // Validação das credenciais de login
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            // Verificar se o usuário existe
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Credenciais inválidas.'], 401);
            }

            // Criação do token
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'token'   => $token,
                'user'    => $user,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao realizar o login: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao realizar login. Tente novamente mais tarde.'], 500);
        }
    }

    public function logout(Request $request)
{
    $user = $request->user();

    // Revoga todos os tokens do usuário
    $user->tokens()->delete();

    return response()->json(['message' => 'Logout realizado com sucesso.']);
}

}
