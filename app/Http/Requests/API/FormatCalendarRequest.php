<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class FormatCalendarRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'day' => 'required|date|after_or_equal:today',
            'services' => 'required|json',
        ];
    }

    public function messages(): array
    {
        return [
            'day.required' => 'Vui lòng chọn ngày.',
            'day.date' => 'Ngày không hợp lệ.',
            'day.after_or_equal' => 'Ngày phải từ hôm nay trở đi.',
            'services.required' => 'Vui lòng chọn dịch vụ.',
        ];
    }
}
