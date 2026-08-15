<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::query()->delete();

        $categories = [
            [
                'id' => 1,
                'name' => 'Bảo Dưỡng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Sửa chữa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Category::query()->insert($categories);
    }
}
