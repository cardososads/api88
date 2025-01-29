<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * GET /api/v1/users
     */
    public function index()
    {
        // Exemplo simples: listando todos os users
        // (Filtre se quiser multitenancy: ex se cnpj = auth()->user()->cnpj)
        $users = User::with('group', 'metadata')->get();
        // Retorna como Resource
        return UserResource::collection($users);
    }

    /**
     * POST /v1/users
     */
    public function store(StoreUserRequest $request): UserResource|JsonResponse
    {
        try {
            $dto = new UserDTO(
                name: $request->validated('name'),
                email: $request->validated('email'),
                password: $request->validated('password'),
                group_id: $request->validated('group_id'),
                cpf: $request->validated('cpf'),
                mentor_id: $request->validated('mentor_id'),
                metadata: $request->validated('metadata') ?? []
            );

            $user = $this->userService->createUser($dto);
            return new UserResource($user);

        } catch (Exception $e) {
            Log::error('Falha ao criar usuário: ' . $e->getMessage());
            return response()->json(['error' => 'Erro interno ao criar usuário.'], 500);
        }
    }


    /**
     * GET /v1/users/{user}
     */
    public function show(User $user)
    {
        $user->load('group', 'metadata');
        return new UserResource($user);
    }

    /**
     * PUT/PATCH /v1/users/{user}
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $dto = new UserDTO(
                name: $request->validated('name') ?? $user->name,
                email: $request->validated('email') ?? $user->email,
                password: $request->validated('password') ?? null,
                group_id: $request->validated('group_id') ?? $user->group_id,
                cpf: $request->validated('cpf') ?? $user->cpf,
                mentor_id: $request->validated('mentor_id') ?? $user->mentor_id,
                metadata: $request->validated('metadata') ?? []
            );

            $updatedUser = $this->userService->updateUser($user, $dto);
            $updatedUser->load('group', 'metadata');

            return response()->json(new UserResource($updatedUser), 200);
        } catch (Exception $e) {
            // Log do erro para análise futura
            Log::error('Erro ao atualizar usuário: ' . $e->getMessage());

            return response()->json(['error' => 'Erro ao atualizar usuário.'], 500);
        }
    }

    /**
     * DELETE /v1/users/{user}
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuário removido com sucesso']);
    }
}
