<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CustomerImport implements ToCollection, ToModel, WithStartRow
{
    public function model(array $row): Model|Customer|null
    {
        return Customer::query()->updateOrCreate(
            [
                'email' => $row[1],
            ],
            [
                'customer_name' => $row[0],
                'email' => $row[1],
                'tel_num' => $row[2],
                'address' => $row[3],
                'is_active' => 1,
            ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string|max:255',
            '1' => 'required|email|unique:mst_customers,email',
            '2' => 'required|digits:10',
            '3' => 'required|string',
            '4' => 'nullable|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            '0.required' => 'Vui lòng nhập tên khách hàng.',
            '0.string' => 'Tên khách hàng phải là chuỗi ký tự.',
            '0.max' => 'Tên khách hàng không được vượt quá 255 ký tự.',

            '1.required' => 'Vui lòng nhập email.',
            '1.email' => 'Email không đúng định dạng.',
            '1.unique' => 'Email này đã tồn tại trong hệ thống.',

            '2.required' => 'Vui lòng nhập số điện thoại.',
            '2.numeric' => 'Số điện thoại chỉ được chứa chữ số.',
            '2.digits' => 'Số điện thoại phải gồm đúng 10 chữ số.',

            '3.required' => 'Vui lòng nhập địa chỉ.',
            '3.string' => 'Địa chỉ phải là chuỗi ký tự.',

            '4.in' => 'Trạng thái không hợp lệ.',
        ];
    }

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $collection) {}
}
