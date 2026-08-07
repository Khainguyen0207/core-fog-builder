<?php

namespace App\Http\Requests\Admin;

use App\Enums\BookingStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(BookingStatusEnum::cases())],
            'note' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
