<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'group_id' => 'required|exists:groups,id',
            'cpf' => 'required|string|max:14|cpf|unique:users,cpf',
            'mentor_id' => 'nullable|exists:users,id',
            'metadata' => 'nullable|array'
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Esse email ja está em utilização.'
        ];
    }
}
