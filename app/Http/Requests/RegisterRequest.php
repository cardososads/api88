<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'cpf'      => 'nullable|string|cpf|unique:users,cpf',
            'metadata' => 'array'
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'O e-mail fornecido já está em uso.',
            'password.min' => 'O campo de senha deve ter pelo menos 8 caracteres.',
            'cpf.unique' => 'O CPF fornecido já está em uso.',
        ];
    }

}
