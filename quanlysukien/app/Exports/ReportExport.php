<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $eventId;
    protected $faculty;
    protected $class;
    protected $status; // ✅ thêm thuộc tính mới

    public function __construct($eventId, $faculty = null, $class = null, $status = null)
    {
        $this->eventId = $eventId;
        $this->faculty = $faculty;
        $this->class = $class;
        $this->status = $status; // ✅ gán thêm
    }

    // ✅ Lấy dữ liệu theo sinh viên, có lọc theo Khoa / Lớp / Trạng thái
    public function collection()
    {
        $query = DB::table('event_registration')
            ->join('users', 'event_registration.user_id', '=', 'users.user_id')
            ->leftJoin('attendance', function ($join) {
                $join->on('users.user_id', '=', 'attendance.user_id')
                    ->where('attendance.event_id', '=', $this->eventId);
            })
            ->where('event_registration.event_id', $this->eventId)
            ->select(
                'users.username',
                'users.full_name',
                'users.class',
                'users.faculty',
                DB::raw("CASE 
                    WHEN attendance.attendance_id IS NOT NULL THEN 'Đã điểm danh'
                    ELSE 'Chưa điểm danh'
                END as status"),
                'attendance.checkin_time'
            );

        // ✅ Áp dụng bộ lọc nếu có
        if (!empty($this->faculty)) {
            $query->where('users.faculty', $this->faculty);
        }

        if (!empty($this->class)) {
            $query->where('users.class', $this->class);
        }

        // ✅ Thêm lọc trạng thái điểm danh
        if ($this->status === 'attended') {
            $query->whereNotNull('attendance.attendance_id');
        } elseif ($this->status === 'not') {
            $query->whereNull('attendance.attendance_id');
        }

        return $query->orderBy('users.class')->orderBy('users.full_name')->get();
    }

    // ✅ Tiêu đề các cột trong file Excel
    public function headings(): array
    {
        return [
            'Mã sinh viên',
            'Họ tên',
            'Lớp',
            'Khoa',
            'Trạng thái điểm danh',
            'Thời gian check-in',
        ];
    }

    // ✅ Định dạng từng dòng trong file Excel
    public function map($row): array
    {
        return [
            $row->username,
            $row->full_name,
            $row->class,
            $row->faculty,
            $row->status,
            $row->checkin_time
                ? date('H:i:s d/m/Y', strtotime($row->checkin_time))
                : '',
        ];
    }
}
