<?php

namespace App\Http\Controllers;

// app/Http/Controllers/AuthController.php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['token' => $user->createToken('api-token')->plainTextToken]);
    }

    public function login(Request $request)
    {
        // Encontra o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        // Verifica as credenciais
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        // Gera o token e retorna a resposta com a mensagem e o token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'token' => $token
        ]);
    }
}
