<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Cria um usuário no banco (para registro),
     * e retorna o modelo do User.
     */
    public function registerUser(array $data): User
    {
        // Exemplo simples.
        // Se quiser usar DTOs, também pode.
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'cpf'      => $data['cpf']
        ]);

        // Se vier metadados:
        if (!empty($data['metadata'])) {
            foreach ($data['metadata'] as $key => $value) {
                $user->metadata()->create([
                    'key' => $key,
                    'value' => $value
                ]);
            }
        }

        return $user;
    }
}
