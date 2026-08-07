<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserGroupRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'string',
                'min:6',
                'confirmed',
            ],

            'group_role' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                Rule::in(UserGroupRoleEnum::cases()),
            ],

            'is_active' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                Rule::in([0, 1]),
            ],
        ];
    }
}
