<?php

namespace App\Http\Requests\Admin;

use App\Enums\BaseStatusEnum;
use App\Enums\TimeUnitEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', Rule::in(BaseStatusEnum::cases())],
            'price' => ['required', 'numeric', 'min:0'],
            'time_do' => ['required', 'integer', 'min:0'],
            'time_unit' => ['required', Rule::in(TimeUnitEnum::cases())],
            'priority' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
