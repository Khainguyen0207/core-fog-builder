<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class StaffReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_service_id' => ['required', 'exists:booking_services,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_service_id.required' => 'Vui lòng chọn dịch vụ cần đánh giá.',
            'booking_service_id.exists' => 'Dịch vụ không tồn tại.',
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer' => 'Số sao phải là số nguyên.',
            'rating.between' => 'Số sao phải từ 1 đến 5.',
            'note.max' => 'Ghi chú không được quá 1000 ký tự.',
        ];
    }

    public function attributes(): array
    {
        return [
            'booking_service_id' => 'dịch vụ',
            'rating' => 'số sao',
            'note' => 'ghi chú',
        ];
    }
}
