<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;

class AutenticaController extends Controller
{
    public function registrar(Request $request)
    {

        try {
            // Validação dos dados
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:6'
            ]);

            // Se a validação falhar, retorna erro
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $dadosUsuario = new User;

            $dadosUsuario = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(60)
            ]);

            return response()->json(['message' => 'Usuário registrado com sucesso!', 'user' => $dadosUsuario], 201);

        } catch (Exception $e) {
            return response()->json(['error' => 'Erro ao registrar usuário', 'message' => $e->getMessage()], 500);
        }
    }


    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }


        // Atualiza o token do usuário no login
        $user->api_token = Str::random(60);
        $user->save();

        return response()->json([
            'message' => 'Login bem-sucedido!',
            'token' => $user->api_token
        ]);
    }

    public function logout(Request $request)
    {
        $user = User::where('api_token', $request->api_token)->first();

        if ($user) {
            $user->api_token = null;
            $user->save();
            return response()->json(['message' => 'Logout realizado com sucesso!']);
        }

        return response()->json(['message' => 'Token inválido'], 401);
    }
}
