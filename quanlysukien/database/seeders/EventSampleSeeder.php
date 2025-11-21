<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventSampleSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Lấy 1 admin làm manager (nếu không có, sẽ để NULL)
        $adminId = DB::table('users')->where('role', 'admin')->value('user_id');

        $rows = [
            // ===== ĐANG DIỄN RA: start <= now <= end =====
            [
                'event_code'       => 'EVT-JOBFAIR',
                'event_name'       => 'Ngày hội việc làm 2025',
                'organizer'        => 'Phòng CTSV',
                'manager_id'       => $adminId,
                'level'            => 'Cấp trường',
                'semester'         => 'HK1',
                'academic_year'    => '2025-2026',
                'start_time'       => $now->copy()->subHours(2),   // bắt đầu 2h trước
                'end_time'         => $now->copy()->addHours(4),   // kết thúc sau 4h
                'location'         => 'Hội trường A',
                'description'      => 'Kết nối doanh nghiệp và sinh viên. Gian hàng tuyển dụng, workshop kỹ năng.',
                'max_participants' => 500,
                'created_at'       => $now,
            ],
            [
                'event_code'       => 'EVT-SEMINARAI',
                'event_name'       => 'Seminar: Ứng dụng AI trong Tài chính',
                'organizer'        => 'Khoa CNTT',
                'manager_id'       => $adminId,
                'level'            => 'Cấp khoa',
                'semester'         => 'HK1',
                'academic_year'    => '2025-2026',
                'start_time'       => $now->copy()->subDay()->addHours(1), // hôm qua +1h (đang diễn ra)
                'end_time'         => $now->copy()->addDay()->addHours(2), // ngày mai +2h
                'location'         => 'Phòng A305',
                'description'      => 'Chia sẻ nghiên cứu và case study AI trong phân tích rủi ro.',
                'max_participants' => 120,
                'created_at'       => $now,
            ],

            // ===== SẮP DIỄN RA: start > now =====
            [
                'event_code'       => 'EVT-ORIENTATION',
                'event_name'       => 'Chào tân sinh viên K27',
                'organizer'        => 'Đoàn Thanh niên',
                'manager_id'       => $adminId,
                'level'            => 'Cấp đơn vị',
                'semester'         => 'HK1',
                'academic_year'    => '2025-2026',
                'start_time'       => $now->copy()->addDays(2)->setTime(8, 0),
                'end_time'         => $now->copy()->addDays(2)->setTime(11, 0),
                'location'         => 'Sân khấu ngoài trời',
                'description'      => 'Giới thiệu chương trình học, hoạt động CLB, hướng dẫn thủ tục.',
                'max_participants' => 800,
                'created_at'       => $now,
            ],
            [
                'event_code'       => 'EVT-RESEARCH',
                'event_name'       => 'Hội thảo Nghiên cứu Khoa học Sinh viên',
                'organizer'        => 'Phòng KHCN',
                'manager_id'       => $adminId,
                'level'            => 'Cấp trường',
                'semester'         => 'HK1',
                'academic_year'    => '2025-2026',
                'start_time'       => $now->copy()->addDays(7)->setTime(13, 30),
                'end_time'         => $now->copy()->addDays(7)->setTime(17, 0),
                'location'         => 'Hội trường B',
                'description'      => 'Trình bày đề tài xuất sắc, trao giải thưởng nghiên cứu.',
                'max_participants' => 300,
                'created_at'       => $now,
            ],
            [
                'event_code'       => 'EVT-SKILLS',
                'event_name'       => 'Workshop: Kỹ năng thuyết trình',
                'organizer'        => 'CLB Kỹ năng mềm',
                'manager_id'       => $adminId,
                'level'            => 'Cấp đơn vị',
                'semester'         => 'HK1',
                'academic_year'    => '2025-2026',
                'start_time'       => $now->copy()->addDays(10)->setTime(9, 0),
                'end_time'         => $now->copy()->addDays(10)->setTime(11, 30),
                'location'         => 'Phòng A207',
                'description'      => 'Thực hành thuyết trình, phản biện và ngôn ngữ cơ thể.',
                'max_participants' => 60,
                'created_at'       => $now,
            ],
        ];

        // upsert theo event_code (unique)
        DB::table('events')->upsert(
            array_map(function ($r) {
                // Đảm bảo kiểu thời gian là string Y-m-d H:i:s khi insert thủ công
                $r['start_time'] = Carbon::parse($r['start_time'])->format('Y-m-d H:i:s');
                $r['end_time']   = Carbon::parse($r['end_time'])->format('Y-m-d H:i:s');
                return $r;
            }, $rows),
            ['event_code'],
            [
                'event_name',
                'organizer',
                'manager_id',
                'level',
                'semester',
                'academic_year',
                'start_time',
                'end_time',
                'location',
                'description',
                'max_participants',
                'created_at'
            ]
        );
    }
}
