<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckAttendanceReminder extends Command
{
    protected $signature = 'events:remind';
    protected $description = 'Nhắc nhở sinh viên chưa điểm danh khi sự kiện bắt đầu';

    public function handle()
    {
        $now = now();

        // Lấy các sự kiện đang diễn ra
        $events = DB::table('events')
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->get();

        foreach ($events as $event) {
            // Lấy sinh viên đăng ký mà chưa điểm danh
            $students = DB::table('event_registration')
                ->where('event_id', $event->event_id)
                ->whereNotIn('user_id', function ($q) use ($event) {
                    $q->select('user_id')
                        ->from('attendance')
                        ->where('event_id', $event->event_id);
                })
                ->pluck('user_id');

            foreach ($students as $uid) {
                DB::table('notifications')->insert([
                    'user_id' => $uid,
                    'title' => 'Nhắc điểm danh sự kiện: ' . $event->event_name,
                    'message' => 'Sự kiện đang diễn ra, bạn chưa điểm danh!',
                    'type' => 'reminder',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->info('✅ Đã gửi thông báo nhắc nhở cho sinh viên chưa điểm danh.');
    }
}
