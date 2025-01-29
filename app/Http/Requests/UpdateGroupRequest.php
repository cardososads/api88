<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
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
        $group = $this->route('group');
        $groupId = $group->id; // UUID

        return [
            'name' => 'sometimes|string|unique:groups,name,'.$groupId.',id',
            'permissions'   => 'array',
            'permissions.*' => 'exists:permissions,id'
        ];
    }

}
