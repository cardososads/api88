<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * POST /api/v1/register
     */
    public function register(RegisterRequest $request)
    {
        // Validação já foi feita por RegisterRequest
        $data = $request->validated();
        // Se tiver metadados no request:
        if ($request->has('metadata')) {
            $data['metadata'] = $request->get('metadata');
        }

        $user = $this->authService->registerUser($data);

        $user->load('metadata');

        // Gera token Sanctum
        $token = $user->createToken('API Token')->plainTextToken;

        // Retorna user + token
        // Se estiver usando UserResource (ver próximo passo),
        // encapsulamos o user.
        return response()->json([
            'user'  => new UserResource($user),
            'token' => $token
        ], 201);
    }

    /**
     * POST /api/v1/login
     */
    public function login(LoginRequest $request)
    {
        // Validação via LoginRequest (email, password).
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'user'  => new UserResource($user),
            'token' => $token
        ]);
    }

    /**
     * POST /api/v1/logout
     */
    public function logout(Request $request)
    {
        // Invalida o token atual
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}
