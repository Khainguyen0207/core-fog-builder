<?php

namespace App\Http\Requests\Admin;

use App\Enums\BasicStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MembershipSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'min_points' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(BasicStatusEnum::cases())],
            'description' => ['nullable', 'string'],
        ];
    }
}
