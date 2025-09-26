<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'players' => UserResource::collection($users)
        ]);
    }

    public function store(RegisterRequest $register)
    {
        try{
            $validated = $register->validated();

            // Crear el usuario
            $user = User::create([
                'name' => $validated['name'],
                'ape' => $validated['ape'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createAuthToken('registration-token');

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado exitosamente',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'ape' => $user->ape,
                    'email' => $user->email,
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                ],
                'token' => $token->plainTextToken, // Token para el frontend
                'token_type' => 'Bearer',
            ], 201);


        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar usuario',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            // Los datos ya están validados por LoginRequest
            $credentials = $request->only(['email', 'password']);

            // Verificar credenciales
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciales incorrectas',
                ], 401);
            }
            
            /** @var User $user */
            $user = Auth::user();

            // Opcional: Revocar tokens anteriores
            // $user->tokens()->delete();

            // Crear nuevo token
            $token = $user->createAuthToken('login-token');

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'ape' => $user->ape,
                    'email' => $user->email,
                    'last_login' => now()->format('Y-m-d H:i:s'),
                ],
                'token' => $token->plainTextToken,
                'token_type' => 'Bearer',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
