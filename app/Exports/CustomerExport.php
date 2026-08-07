<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection(): Collection
    {
        $filters = [
            'customer_name',
            'email',
            'address',
            'is_active',
        ];

        $filterData = collect(request()->all())
            ->only($filters)
            ->filter(fn ($value) => $value !== null && $value !== '');

        $query = Customer::query()->select([
            'customer_name',
            'email',
            'tel_num',
            'address',
        ]);

        foreach ($filterData as $column => $value) {
            if ($column === 'is_active') {
                $query->where($column, $value);
            } else {
                $query->where($column, 'like', "%{$value}%");
            }
        }

        return $query->latest('customer_id')->limit(20)->get();
    }

    public function headings(): array
    {
        return [
            'Tên Khách Hàng',
            'Email',
            'Số điện thoại',
            'Địa chỉ',
        ];
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
        ]);

        $sheet->getStyle('A2:D'.$sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'vertical' => 'center',
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
        ]);

        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(40);
    }
}
