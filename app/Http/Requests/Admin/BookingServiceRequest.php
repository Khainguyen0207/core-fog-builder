<?php

namespace App\Http\Requests\Admin;

use App\Enums\BookingStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingServiceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'exists:bookings,id'],
            'service_id' => [
                'required',
                'exists:services,id',
            ],
            'service_name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(BookingStatusEnum::cases())],
            'assigned_staff_id' => ['nullable', 'exists:staffs,id'],
            'note' => ['nullable', 'string'],
            'started_at' => ['required', 'date'],
            'finished_at' => ['nullable', 'date', 'after_or_equal:started_at'],
        ];
    }

    public function attributes(): array
    {
        return [
            'booking_id' => 'đặt lịch',
            'service_id' => 'dịch vụ',
            'service_name' => 'tên dịch vụ',
            'started_at' => 'thời gian bắt đầu',
            'finished_at' => 'thời gian kết thúc',
        ];
    }
}
