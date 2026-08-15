<?php

namespace Database\Seeders;

use App\Enums\BaseStatusEnum;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'id' => 6,
                'title' => 'Thay nhớt',
                'subtitle' => 'Bôi trơn & bảo vệ động cơ',
                'description' => '
            <p><strong>Thay nhớt</strong> là dịch vụ giúp động cơ vận hành êm, giảm ma sát và hạn chế nóng máy.</p>
            <p><strong>Quy trình thực hiện:</strong></p>
            <ul>
                <li>Kiểm tra mức nhớt hiện tại và tình trạng nhớt (màu, độ loãng, cặn bẩn).</li>
                <li>Xả nhớt cũ và vệ sinh khu vực ốc xả để tránh rò rỉ sau thay.</li>
                <li>Châm nhớt mới đúng dung tích và đúng cấp nhớt phù hợp với xe.</li>
                <li>Khởi động kiểm tra lại, đảm bảo không rò rỉ và máy chạy êm.</li>
            </ul>
            <p><em>Khuyến nghị:</em> Thay định kỳ theo km hoặc thời gian sử dụng để giữ máy bền.</p>
        ',
                'category_id' => 1,
                'status' => BaseStatusEnum::ENABLED,            
                'price' => 120000,
                'time_do' => 30,
                'time_unit' => 'minute',
                'priority' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'title' => 'Thay bugi',
                'subtitle' => 'Đánh lửa ổn định, dễ nổ máy',
                'description' => '
            <p><strong>Thay bugi</strong> giúp cải thiện khả năng đánh lửa, xe dễ đề và đốt nhiên liệu hiệu quả hơn.</p>
            <p><strong>Quy trình thực hiện:</strong></p>
            <ul>
                <li>Tháo bugi cũ và kiểm tra tình trạng (muội than, mòn điện cực, cháy đầu bugi).</li>
                <li>Vệ sinh khu vực cổ bugi, kiểm tra chụp bugi/dây bugi (nếu có) để tránh đánh lửa yếu.</li>
                <li>Lắp bugi mới đúng chuẩn loại xe, siết đúng lực để tránh tuôn ren hoặc hở hơi.</li>
                <li>Khởi động kiểm tra: tiếng nổ, độ ổn định ga-răng-ti và độ bốc.</li>
            </ul>
            <p><em>Dấu hiệu nên thay:</em> xe khó nổ, hao xăng, giật khi tăng ga, máy rung bất thường.</p>
        ',
                'category_id' => 1,
                'status' => BaseStatusEnum::ENABLED,
                'price' => 80000,
                'time_do' => 20,
                'time_unit' => 'minute',
                'priority' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'title' => 'Sửa phanh',
                'subtitle' => 'Kiểm tra & xử lý hệ thống phanh',
                'description' => '
            <p><strong>Sửa phanh</strong> tập trung vào việc kiểm tra và khắc phục các lỗi khiến phanh kém ăn, kêu, bó phanh hoặc mất an toàn.</p>
            <p><strong>Nội dung kiểm tra & xử lý:</strong></p>
            <ul>
                <li>Kiểm tra độ mòn <strong>má phanh</strong> (trước/sau) và tình trạng đĩa/tang trống phanh.</li>
                <li>Kiểm tra <strong>dầu phanh</strong> (mức dầu, màu dầu), phát hiện rò rỉ ở heo phanh/ống dầu.</li>
                <li>Căn chỉnh hành trình phanh, xử lý tình trạng <strong>phanh kêu</strong>, <strong>phanh bó</strong> hoặc <strong>phanh ăn không đều</strong>.</li>
                <li>Test thực tế sau sửa để đảm bảo phanh ổn định và an toàn khi vận hành.</li>
            </ul>
            <p><em>Lưu ý:</em> Chi phí có thể thay đổi tùy hạng mục cần thay thế (má phanh, dầu phanh, linh kiện).</p>
        ',
                'category_id' => 2,
                'status' => BaseStatusEnum::ENABLED,
                'price' => 150000,
                'time_do' => 45,
                'time_unit' => 'minute',
                'priority' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'title' => 'Bảo dưỡng',
                'subtitle' => 'Kiểm tra tổng thể xe',
                'description' => '
            <p><strong>Bảo dưỡng tổng quát</strong> là gói kiểm tra định kỳ giúp phát hiện sớm vấn đề và giữ xe hoạt động ổn định.</p>
            <p><strong>Các hạng mục thường gồm:</strong></p>
            <ul>
                <li>Kiểm tra tổng quan: rò rỉ dầu/nhớt, tiếng máy, độ rung, tình trạng dây/cáp.</li>
                <li>Kiểm tra <strong>phanh</strong>: độ mòn má, hành trình phanh, dầu phanh (nếu có).</li>
                <li>Kiểm tra <strong>lốp</strong>: áp suất, độ mòn, tình trạng vỏ và van.</li>
                <li>Kiểm tra <strong>điện</strong>: bình ắc quy, đèn, còi, đề và sạc.</li>
                <li>Kiểm tra <strong>nhông sên dĩa</strong> (xe số): độ chùng sên, bôi trơn và căn chỉnh cơ bản.</li>
                <li>Siết lại các vị trí cơ bản (ốc/đai) và tư vấn hạng mục cần thay nếu phát hiện bất thường.</li>
            </ul>
            <p><em>Phù hợp:</em> xe đi hằng ngày, xe lâu không kiểm tra, hoặc trước chuyến đi xa.</p>
        ',
                'category_id' => 1,
                'status' => BaseStatusEnum::ENABLED,
                'price' => 200000,
                'time_do' => 60,
                'time_unit' => 'minute',
                'priority' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'title' => 'Rửa xe',
                'subtitle' => 'Rửa xe & làm sạch',
                'description' => '
            <p><strong>Rửa xe & làm sạch</strong> giúp xe sạch sẽ, giảm bám bẩn lâu ngày và tăng thẩm mỹ khi sử dụng.</p>
            <p><strong>Nội dung dịch vụ:</strong></p>
            <ul>
                <li>Xịt rửa bụi bẩn, bùn đất bám ở thân vỏ và bánh xe.</li>
                <li>Làm sạch các chi tiết bên ngoài: dàn áo, mâm, gầm xe (mức cơ bản).</li>
                <li>Lau khô và vệ sinh hoàn thiện để xe gọn gàng, sạch sẽ.</li>
            </ul>
            <p><em>Lưu ý:</em> Không bao gồm đánh bóng chuyên sâu hoặc vệ sinh chi tiết khoang máy (nếu có sẽ là dịch vụ riêng).</p>
        ',
                'category_id' => 1,
                'status' => BaseStatusEnum::ENABLED,
                'price' => 30000,
                'time_do' => 15,
                'time_unit' => 'minute',
                'priority' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Service::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Service::query()->insert($services);
    }
}
