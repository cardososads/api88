<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\CPF;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true; // Ajuste conforme necessário, por exemplo, verificar permissões do usuário
    }

    /**
     * Obter as regras de validação que se aplicam à requisição.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        // Obtém o ID do usuário atual da rota
        $userId = $this->route('user')->id;

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => 'sometimes|required|string|min:6',
            'group_id' => 'nullable|exists:groups,id',
            'cpf' => [
                'sometimes',
                'string',
                'max:14',
                Rule::unique('users', 'cpf')->ignore($userId)
            ],
            'mentor_id' => 'nullable|exists:users,id',
            'metadata' => 'nullable|array',
            'metadata.foto_perfil' => 'nullable|string',
            'metadata.mini_bio' => 'nullable|string|max:500',
        ];
    }
}
