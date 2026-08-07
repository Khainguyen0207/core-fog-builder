<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'staff_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('staffs', 'staff_code')->ignore($this->route('staff')),
            ],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^0\d{9}$/'],
            'level' => ['required', Rule::in(\App\Enums\StaffLevelEnum::cases())],
            'is_active' => ['boolean'],
            'salary' => ['required', 'numeric', 'min:0'],
            'joined_at' => ['required', 'date'],
            'resigned_at' => ['nullable', 'date', 'after_or_equal:joined_at'],
            'note' => ['nullable', 'string'],
            'avatar' => ['nullable', 'file', 'max:2048', 'mimes:jpeg,png,jpg,gif,webp'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['exists:services,id'],
        ];

        if ($this->method() === "POST") {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }
}
