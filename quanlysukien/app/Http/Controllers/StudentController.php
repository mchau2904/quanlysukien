<?php
// app/Http/Controllers/StudentController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    // app/Http/Controllers/StudentController.php
   public function index(Request $request)
    {
        $q        = trim((string) $request->get('q', ''));
        $faculty  = $request->get('faculty');
        $class    = $request->get('class');
        $sort     = $request->get('sort', 'desc');

        $students = \App\Models\User::where('role', 'student')
            ->leftJoin(DB::raw('(SELECT user_id, COUNT(DISTINCT event_id) AS total_events FROM attendance GROUP BY user_id) AS att'),
                'users.user_id', '=', 'att.user_id')
            ->select('users.*', DB::raw('COALESCE(att.total_events, 0) AS total_events'))
            ->when($q, fn($q2) => $q2->where(function($w) use ($q) {
                $w->where('users.full_name', 'like', "%$q%")
                ->orWhere('users.username', 'like', "%$q%");
            }))
            ->when($faculty && $faculty !== 'Tất cả', fn($q2) => $q2->where('users.faculty', $faculty))
            ->when($class && $class !== 'Tất cả', fn($q2) => $q2->where('users.class', $class))
            ->orderBy('total_events', $sort) // 🔥 sắp xếp theo số sự kiện đã tham gia
            ->paginate(10)
            ->withQueryString();

        $faculties = DB::table('users')->where('role', 'student')->whereNotNull('faculty')->distinct()->pluck('faculty');
        $classes   = DB::table('users')->where('role', 'student')->whereNotNull('class')->distinct()->pluck('class');

        return view('students.index', compact('students', 'q', 'faculty', 'class', 'faculties', 'classes', 'sort'));
    }


    // 👇 Thêm trang chi tiết sinh viên
    public function show(User $user)
{
    abort_unless($user->role === 'student', 404);

    $events = DB::table('attendance as a')
        ->join('events as e', 'a.event_id', '=', 'e.event_id')
        ->select(
            'e.event_name',
            'e.start_time',
            'e.end_time',
            'e.location as event_location',
            'a.checkin_time',
            'a.checkout_time',
            'a.status',
            'a.image_url'
        )
        ->where('a.user_id', $user->user_id)
        ->orderByDesc('e.start_time')
        ->get();

    return view('students.show', compact('user', 'events'));
}





    public function create()
    {
        return view('students.create');
    }

    // Thêm mới: đặt password mặc định SHA1('12345678') qua DB::table để tránh bị bcrypt trong Model
    public function store(Request $request)
    {
        $data = $request->validate([
            'username'  => 'required|string|max:50|unique:users,username',
            'full_name' => 'required|string|max:100',
            'dob'       => 'nullable|date',
            'gender'    => 'nullable|in:Nam,Nữ,Khác',
            'phone'     => 'nullable|string|max:15',
            'email'     => 'nullable|email|max:100',
            'class'     => 'nullable|string|max:50',
            'faculty'   => 'nullable|string|max:100',
        ]);

        DB::table('users')->insert([
            'username'   => $data['username'],
            'password'   => sha1('12345678'), // mật khẩu mặc định
            'full_name'  => $data['full_name'],
            'dob'        => $data['dob'] ?? null,
            'gender'     => $data['gender'] ?? null,
            'phone'      => $data['phone'] ?? null,
            'email'      => $data['email'] ?? null,
            'class'      => $data['class'] ?? null,
            'faculty'    => $data['faculty'] ?? null,
            'role'       => 'student',
            'created_at' => now(),
        ]);

        return redirect()->route('students.index')->with('status', 'Thêm sinh viên thành công (mật khẩu mặc định: 12345678).');
    }

    public function edit(User $user)
    {
        // đảm bảo đúng role
        abort_unless($user->role === 'student', 404);
        return view('students.edit', compact('user'));
    }

    // Cập nhật: không đụng vào password để tránh băm bcrypt trong Model
    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'student', 404);

        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'dob'       => 'nullable|date',
            'gender'    => 'nullable|in:Nam,Nữ,Khác',
            'phone'     => 'nullable|string|max:15',
            'email'     => 'nullable|email|max:100',
            'class'     => 'nullable|string|max:50',
            'faculty'   => 'nullable|string|max:100',
        ]);

        $user->fill($data);
        $user->save();

        return redirect()->route('students.index')->with('status', 'Cập nhật sinh viên thành công.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'student', 404);
        $user->delete();
        return back()->with('status', 'Đã xoá sinh viên.');
    }
   public function export(Request $request)
    {
        $q        = trim((string) $request->get('q', ''));
        $faculty  = $request->get('faculty');
        $class    = $request->get('class');
        $sort     = $request->get('sort', 'desc');

        $fileName = 'Danh_sach_sinh_vien_' . now()->format('Ymd_His') . '.xlsx';

        // ✅ truyền filter & sort vào export class
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\StudentsExport($q, $faculty, $class, $sort),
            $fileName
        );
    }

    public function bulkDelete(Request $request)
    {
        $ids = explode(',', $request->input('ids', ''));

        if (empty($ids)) {
            return back()->with('status', '⚠️ Không có sinh viên nào được chọn.');
        }

        \App\Models\User::whereIn('user_id', $ids)->where('role', 'student')->delete();

        return back()->with('status', '🗑️ Đã xoá ' . count($ids) . ' sinh viên thành công.');
    }
}
