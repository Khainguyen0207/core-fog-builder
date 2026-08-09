<?php

namespace Modules\Customers\Http\Requests;

use App\Enums\BasicStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MembershipSettingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'membership_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('membership_settings', 'membership_code')->ignore($this->route('membership_setting')),
            ],
            'name' => ['required', 'string', 'max:255'],
            'min_points' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(BasicStatusEnum::cases())],
            'description' => ['nullable', 'string'],
        ];
    }
}
