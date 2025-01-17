<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // User registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'), 201);
    }

    // User login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }

            $user = auth()->user();

            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json(compact('user' ,'token'));
        } catch (JWTException $e) {
            return response()->json(['error' => 'Não foi possível criar o token'], 500);
        }
    }

    // Get authenticated user
    public function getUser()
    {

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'Usuário não existe'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token inválido'], 400);
        }

        return response()->json(compact('user'));
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Desconectado com sucesso']);
    }

    //get user by id
    public function getUserById(int $userId)
    {
        try {
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['message' => 'Usuário não encontrado'], 404);
            }

            return response()->json(compact('user'), 200);

        } catch (\Exception $e) {
            Log::error("Erro ao buscar usuário: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao buscar usuário'], 500);
        }
    }

    // update user role
    public function updateUserRole($id, Request $request)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'Usuário não encontrado'], 404);
            }

            $user->role = $request->input('role');
            $user->save();

            return response()->json(['message' => 'Usuário atualizado com sucesso'], 200);

        } catch (\Exception $e) {
            Log::error("Erro ao atualizar usuário: {$e->getMessage()}");
            return response()->json(['message' => 'Erro ao atualizar usuário'], 500);
        }
    }


}
