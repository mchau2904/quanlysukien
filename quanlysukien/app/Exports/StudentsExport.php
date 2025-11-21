<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    private $index = 0;
    private $q;
    private $faculty;
    private $class;
    private $sort;

    public function __construct($q = null, $faculty = null, $class = null, $sort = 'desc')
    {
        $this->q = $q;
        $this->faculty = $faculty;
        $this->class = $class;
        $this->sort = $sort;
    }

    public function collection()
    {
        return User::where('role', 'student')
            ->leftJoin(
                DB::raw('(SELECT user_id, COUNT(DISTINCT event_id) AS total_events FROM attendance GROUP BY user_id) AS att'),
                'users.user_id',
                '=',
                'att.user_id'
            )
            ->select(
                'users.username',
                'users.full_name',
                'users.class',
                'users.faculty',
                'users.gender',
                DB::raw('COALESCE(att.total_events, 0) AS total_events')
            )
            ->when($this->q, function ($query) {
                $q = $this->q;
                $query->where(function ($w) use ($q) {
                    $w->where('users.full_name', 'like', "%$q%")
                      ->orWhere('users.username', 'like', "%$q%");
                });
            })
            ->when($this->faculty && $this->faculty !== 'Tất cả', function ($query) {
                $query->where('users.faculty', $this->faculty);
            })
            ->when($this->class && $this->class !== 'Tất cả', function ($query) {
                $query->where('users.class', $this->class);
            })
            ->orderBy('total_events', $this->sort)
            ->get();
    }

    public function map($student): array
    {
        $this->index++;

        return [
            $this->index,                // STT
            $student->username,          // MSSV
            $student->full_name,         // Họ tên
            $student->class ?? '—',      // Lớp
            $student->faculty ?? '—',    // Khoa
            $student->gender ?? '—',     // Giới tính
            $student->total_events ?? 0, // Tổng sự kiện
        ];
    }

    public function headings(): array
    {
        return [
            'STT',
            'MSSV',
            'Họ tên',
            'Lớp',
            'Khoa',
            'Giới tính',
            'Tổng sự kiện đã tham gia',
        ];
    }
}
