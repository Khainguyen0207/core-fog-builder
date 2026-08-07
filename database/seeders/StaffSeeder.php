<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffs = [
            [
                'staff_code' => 'STF-0001',
                'user_id' => 6,
                'name' => 'Võ Quốc Khoa',
                'phone' => '0901234561',
                'level' => 'trainee',
                'is_active' => true,
                'salary' => 6000000,
                'avatar' => $this->seedAvatarFromPublic('avt_man_v2.jpg'),
                'joined_at' => '2025-10-01 08:00:00',
                'resigned_at' => null,
                'note' => 'Nhân viên mới, đang trong giai đoạn đào tạo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_code' => 'STF-0002',
                'user_id' => 2,
                'name' => 'Trần Hoàng Minh',
                'phone' => '0901234562',
                'level' => 'junior',
                'is_active' => true,
                'salary' => 8000000,
                'avatar' => $this->seedAvatarFromPublic('avt_man.png'),
                'joined_at' => '2024-06-15 08:00:00',
                'resigned_at' => null,
                'note' => 'Có thể xử lý các dịch vụ cơ bản',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_code' => 'STF-0003',
                'user_id' => 3,
                'name' => 'Lê Thị Thảo',
                'phone' => '0901234563',
                'level' => 'senior',
                'is_active' => true,
                'salary' => 12000000,
                'avatar' => $this->seedAvatarFromPublic('woman_2.jpg'),
                'joined_at' => '2023-03-10 08:00:00',
                'resigned_at' => null,
                'note' => 'Kỹ thuật viên chính, xử lý ca phức tạp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_code' => 'STF-0004',
                'user_id' => 4,
                'name' => 'Nguyễn Thành Đạt',
                'phone' => '0901234564',
                'level' => 'lead',
                'is_active' => true,
                'salary' => 15000000,
                'avatar' => $this->seedAvatarFromPublic('mna_v3.jpg'),
                'joined_at' => '2022-01-05 08:00:00',
                'resigned_at' => null,
                'note' => 'Dẫn dắt đội kỹ thuật, phân công công việc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_code' => 'STF-0005',
                'user_id' => 5,
                'name' => 'Phạm Mỹ Linh',
                'phone' => '0901234565',
                'level' => 'supervisor',
                'is_active' => false,
                'salary' => 18000000,
                'avatar' => $this->seedAvatarFromPublic('woman_1.jpg'),
                'joined_at' => '2020-09-01 08:00:00',
                'resigned_at' => '2025-01-01 18:00:00',
                'note' => 'Quản lý kỹ thuật, đã nghỉ việc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Staff::query()->delete();

        Staff::query()->insert($staffs);
    }

    function seedAvatarFromPublic(string $file): string
    {
        $source = public_path('assets/avatars/' . $file);

        if (!file_exists($source)) {
            throw new RuntimeException("Avatar source not found: {$source}");
        }

        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $randomName = Str::uuid()->toString() . '.' . $extension;

        $target = 'avatars/' . $randomName;

        if (!Storage::disk('public')->exists($target)) {
            Storage::disk('public')->put(
                $target,
                file_get_contents($source)
            );
        }

        return $target;
    }
}
