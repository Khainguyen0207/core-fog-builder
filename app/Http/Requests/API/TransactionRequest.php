<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'customer_phone' => 'required|string',
            'customer_name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Vui lòng nhập số tiền.',
            'amount.numeric' => 'Số tiền không hợp lệ.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'customer_phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'customer_name.required' => 'Vui lòng nhập tên khách hàng.',
            'customer_name.string' => 'Tên khách hàng phải là chuỗi ký tự.',
        ];
    }
}
