<?php

namespace Database\Seeders;

use App\Models\MembershipSetting;
use Illuminate\Database\Seeder;

class MembershipSettingSeeder extends Seeder
{
    public function run(): void
    {
        MembershipSetting::truncate();

        $memberships = [
            [
                'membership_code' => 'default',
                'name' => 'Khách hàng thân thiết',
                'min_points' => 0,
                'status' => 'published',
                'description' => '<div class="ql-editor"><p>Cấp độ mặc định cho khách hàng mới. Bắt đầu hành trình chăm sóc xe cùng chúng tôi.</p></div>',
            ],
            [
                'membership_code' => 'silver',
                'name' => 'Thành viên Bạc (Silver)',
                'min_points' => 100000,
                'status' => 'published',
                'description' => '<div class="ql-editor"><p><strong>Dành cho khách hàng sử dụng dịch vụ định kỳ.</strong></p><ul><li>Giảm giá 2% trên tổng hóa đơn</li><li>Nhắc lịch bảo dưỡng định kỳ</li><li>Kiểm tra tổng quát miễn phí khi đến sửa</li><li>Tham gia các chương trình ưu đãi chung</li></ul></div>',
            ],
            [
                'membership_code' => 'gold',
                'name' => 'Thành viên Vàng (Gold)',
                'min_points' => 500000,
                'status' => 'published',
                'description' => '<div class="ql-editor"><p><strong>Dành cho khách hàng có tần suất sử dụng dịch vụ cao.</strong></p><ul><li>Giảm giá 4% trên tổng hóa đơn</li><li>Ưu tiên xếp lịch sửa nhanh hơn</li><li>Miễn phí kiểm tra xe 1 lần/năm</li><li>Giảm giá phụ tùng chính hãng</li><li>Hỗ trợ cứu hộ nội thành với mức phí ưu đãi</li></ul></div>',
            ],
            [
                'membership_code' => 'diamond',
                'name' => 'Thành viên Kim cương',
                'min_points' => 2000000,
                'status' => 'published',
                'description' => '<div class="ql-editor"><p><strong>Dành cho khách hàng thân thiết lâu năm.</strong></p><ul><li>Giảm giá 8% trên tổng hóa đơn</li><li>Ưu tiên tiếp nhận ngay khi đến cửa hàng</li><li>Miễn phí kiểm tra xe định kỳ</li><li>Miễn phí công thay thế phụ tùng cơ bản</li><li>Cứu hộ nội thành miễn phí </li><li>Hỗ trợ ngoài giờ khi cần thiết</li></ul></div>',
            ],
        ];



        foreach ($memberships as $membership) {
            MembershipSetting::query()->updateOrCreate(
                ['membership_code' => $membership['membership_code']],
                [
                    'name' => $membership['name'],
                    'min_points' => $membership['min_points'],
                    'status' => $membership['status'],
                    'description' => $membership['description'],
                ]
            );
        }
    }
}
