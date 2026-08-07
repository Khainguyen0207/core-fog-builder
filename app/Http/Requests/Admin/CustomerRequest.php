<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->route('customer');

        $rules =  [
            'user_id' => ['nullable', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{10}$/',
                Rule::unique('customers', 'phone')->ignore($userId),
            ],
            'membership_code' => [
                'nullable',
                'string',
                Rule::exists('membership_settings', 'membership_code'),
            ],
            'total_spent' => [
                'required',
                'min:0',
                'regex:/^\d{1,16}(\.\d+)?$/'
            ],
            'note' => ['nullable', 'string'],
        ];

        if ($this->method() === "POST") {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6|confirmed';
        }

        return $rules;
    }
}
