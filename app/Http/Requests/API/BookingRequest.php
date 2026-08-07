<?php

namespace App\Http\Requests\API;

use App\Enums\PaymentMethodEnum;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|min:5',
            'customer_phone' => 'required|string|min:9',
            'scheduled_start' => 'required|date|after_or_equal:now',
            'bike_type' => 'required|string',
            'notify_email' => 'nullable|string|email',
            'plate_number' => 'required|string',
            'services' => 'required|array|min:1',
            'services.*.service_id' => 'required|integer|exists:services,id',
            'services.*.staff_id' => 'nullable|integer|exists:staffs,id',
            'services.*.start_time' => 'nullable|date_format:Y-m-d H:i',
            'services.*.end_time' => 'nullable|date_format:Y-m-d H:i|after:services.*.start_time',
            'note' => 'nullable|string',
            'payment_method' => ['required', Rule::in(PaymentMethodEnum::cases())],
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->scheduled_start) {
            $this->merge([
                'scheduled_start' => Carbon::parse($this->scheduled_start)
                    ->format('Y-m-d H:i:s'),
            ]);
        }

        if (is_string($this->services)) {
            $this->merge([
                'services' => json_decode($this->services, true) ?? [],
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Vui lòng nhập tên khách hàng.',
            'customer_name.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            'customer_name.min' => 'Tên khách hàng phải có ít nhất 5 ký tự.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'customer_phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'customer_phone.min' => 'Số điện thoại phải có ít nhất 9 ký tự.',
            'scheduled_start.required' => 'Vui lòng chọn thời gian bắt đầu.',
            'scheduled_start.date' => 'Thời gian bắt đầu không hợp lệ.',
            'scheduled_start.after_or_equal' => 'Thời gian bắt đầu phải từ hiện tại trở đi.',
            'bike_type.required' => 'Vui lòng nhập loại xe.',
            'bike_type.string' => 'Loại xe phải là chuỗi ký tự.',
            'notify_email.email' => 'Email thông báo không hợp lệ.',
            'notify_email.string' => 'Email thông báo phải là chuỗi ký tự.',
            'plate_number.required' => 'Vui lòng nhập biển số xe.',
            'plate_number.string' => 'Biển số xe phải là chuỗi ký tự.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
            'coupon_code.string' => 'Mã khuyến mãi phải là chuỗi ký tự.',
            'coupon_code.exists' => 'Mã khuyến mãi không tồn tại.',
            'services.required' => 'Vui lòng chọn ít nhất một dịch vụ.',
            'services.min' => 'Vui lòng chọn ít nhất một dịch vụ.',
            'services.*.service_id.required' => 'Mỗi dịch vụ phải có service_id.',
            'services.*.service_id.exists' => 'Dịch vụ không tồn tại.',
            'services.*.staff_id.exists' => 'Nhân viên không tồn tại.',
            'services.*.start_time.date_format' => 'Thời gian bắt đầu không đúng định dạng (Y-m-d H:i).',
            'services.*.end_time.date_format' => 'Thời gian kết thúc không đúng định dạng (Y-m-d H:i).',
            'services.*.end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ];
    }
}
