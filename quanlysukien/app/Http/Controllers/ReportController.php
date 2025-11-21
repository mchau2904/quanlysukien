<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    // 🧾 Trang danh sách tất cả sự kiện
    public function listEvents()
    {
        $now = now();

        $events = DB::table('events')
            ->select('event_id', 'event_code', 'event_name', 'start_time', 'end_time', 'location')
            ->where('end_time', '<', $now) // 🔥 chỉ lấy sự kiện đã kết thúc
            ->orderBy('end_time', 'desc')  // sắp xếp theo thời gian kết thúc mới nhất
            ->get();

        return view('report.list', compact('events'));
    }


    public function showEvent(Request $request, $eventId)
    {
        $event = DB::table('events')->where('event_id', $eventId)->first();
        if (!$event) {
            abort(404, 'Không tìm thấy sự kiện');
        }

        // Nhận giá trị lọc
        $selectedFaculty = $request->query('faculty');
        $selectedClass = $request->query('class');
        $selectedStatus = $request->query('status'); // ✅ thêm

        // Tổng số sinh viên đăng ký
        $queryReg = DB::table('event_registration')
            ->join('users', 'event_registration.user_id', '=', 'users.user_id')
            ->where('event_registration.event_id', $eventId);

        if (!empty($selectedFaculty)) {
            $queryReg->where('users.faculty', $selectedFaculty);
        }
        if (!empty($selectedClass)) {
            $queryReg->where('users.class', $selectedClass);
        }

        $totalStudents = $queryReg->count();

        // Số đã điểm danh
        $attendedCount = DB::table('attendance')
            ->join('users', 'attendance.user_id', '=', 'users.user_id')
            ->where('attendance.event_id', $eventId)
            ->when($selectedFaculty, fn($q) => $q->where('users.faculty', $selectedFaculty))
            ->when($selectedClass, fn($q) => $q->where('users.class', $selectedClass))
            ->distinct('attendance.user_id')
            ->count('attendance.user_id');

        $notAttendedCount = max($totalStudents - $attendedCount, 0);

        // ✅ Dữ liệu biểu đồ (định nghĩa lại để không lỗi)
        $labels = ['Đã điểm danh', 'Chưa điểm danh'];
        $counts = [$attendedCount, $notAttendedCount];

        // Danh sách chi tiết sinh viên
        $studentStats = DB::table('event_registration')
            ->join('users', 'event_registration.user_id', '=', 'users.user_id')
            ->leftJoin('attendance', function ($join) use ($eventId) {
                $join->on('users.user_id', '=', 'attendance.user_id')
                    ->where('attendance.event_id', '=', $eventId);
            })
            ->where('event_registration.event_id', $eventId)
            ->when($selectedFaculty, fn($q) => $q->where('users.faculty', $selectedFaculty))
            ->when($selectedClass, fn($q) => $q->where('users.class', $selectedClass))
            ->when($selectedStatus === 'attended', fn($q) => $q->whereNotNull('attendance.attendance_id'))
            ->when($selectedStatus === 'not', fn($q) => $q->whereNull('attendance.attendance_id'))
            ->select(
                'users.user_id',
                'users.username',
                'users.full_name',
                'users.class',
                'users.faculty',
                DB::raw("CASE 
                WHEN attendance.attendance_id IS NOT NULL THEN 'Đã điểm danh'
                ELSE 'Chưa điểm danh'
            END as status"),
                'attendance.checkin_time',
                'attendance.image_url'
            )
            ->orderBy('users.faculty')
            ->orderBy('users.class')
            ->get();

        // Danh sách tất cả khoa & lớp (để fill dropdown)
        $faculties = DB::table('users')
            ->select('faculty')
            ->whereNotNull('faculty')
            ->distinct()
            ->pluck('faculty');

        $classes = DB::table('users')
            ->select('class')
            ->whereNotNull('class')
            ->distinct()
            ->pluck('class');

        // ✅ trả về view đầy đủ
        return view('report.show', compact(
            'event',
            'labels',
            'counts',
            'studentStats',
            'totalStudents',
            'attendedCount',
            'notAttendedCount',
            'faculties',
            'classes',
            'selectedFaculty',
            'selectedClass',
            'selectedStatus'
        ));
    }




    public function export(Request $request, $eventId)
    {
        $event = DB::table('events')->where('event_id', $eventId)->first();
        if (!$event) {
            abort(404, 'Không tìm thấy sự kiện');
        }

        $faculty = $request->query('faculty');
        $class = $request->query('class');
        $status = $request->query('status'); // ✅ thêm

        $filename = 'Bao_cao_su_kien_' . ($event->event_code ?? 'SU_KIEN') . '_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ReportExport($eventId, $faculty, $class, $status), $filename);
    }
}
