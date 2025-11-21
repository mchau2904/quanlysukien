<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q', ''));

        $base = Event::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('event_name', 'like', "%$q%")
                        ->orWhere('event_code', 'like', "%$q%")
                        ->orWhere('organizer', 'like', "%$q%")
                        ->orWhere('location', 'like', "%$q%");
                });
            });

        $ongoing  = (clone $base)->ongoing()->orderBy('start_time')->get();
        $upcoming = (clone $base)->upcoming()->orderBy('start_time')->get();

        $organizers = Event::select('organizer')
            ->whereNotNull('organizer')
            ->distinct()
            ->orderBy('organizer')
            ->pluck('organizer');

        $adminList = null;
        if (auth()->check() && auth()->user()->role === 'admin') {
            $status = $request->get('status');
            $org    = $request->get('org');
            $sort   = $request->get('sort', 'time_desc');

            $adminQuery = (clone $base)
                ->when($org, fn($qr) => $qr->where('organizer', $org))
                ->when($status === 'ongoing', fn($qr) =>
                $qr->where('start_time', '<=', now())->where('end_time', '>=', now()))
                ->when($status === 'upcoming', fn($qr) =>
                $qr->where('start_time', '>', now()))
                ->when($status === 'past', fn($qr) =>
                $qr->where('end_time', '<', now()));

            $adminQuery->when(
                $sort === 'time_asc',
                fn($qr) => $qr->orderBy('start_time', 'asc'),
                fn($qr) => $qr->orderBy('start_time', 'desc')
            );

            $adminList = $adminQuery->paginate(10)->withQueryString();
        }

        return view('events.index', compact('ongoing', 'upcoming', 'q', 'adminList', 'organizers'));
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

   public function create()
{
    $managers = User::where('role', 'admin')
        ->orderBy('full_name')
        ->get(['user_id', 'full_name', 'username']);

    // ✅ Lấy danh sách lớp distinct từ bảng users
    $classes = DB::table('users')
        ->whereNotNull('class')
        ->distinct()
        ->orderBy('class')
        ->pluck('class');

    // ✅ Danh sách khoa cố định
    $faculties = [
        'Công nghệ thông tin',
        'Kế toán',
        'Ngân hàng',
        'Tài chính',
        'Chất lượng cao',
        'Khác',
        'Tất cả'
    ];

    return view('events.form', [
        'event' => new Event(),
        'managers' => $managers,
        'mode' => 'create',
        'classes' => $classes,
        'faculties' => $faculties,
    ]);
}

    public function store(Request $request)
    {
        $data = $this->validated($request, null);

        // ✅ Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $data['image_url'] = asset('storage/' . $path);
        }

        // ✅ Giới hạn số lượng sinh viên
        $totalStudents = \App\Models\User::where('role', 'student')->count();
        if (!empty($data['max_participants']) && $data['max_participants'] > $totalStudents) {
            return back()
                ->withErrors(['max_participants' => 'Số lượng tối đa không được vượt quá tổng số sinh viên (' . $totalStudents . ').'])
                ->withInput();
        }

        // ✅ Tự sinh mã sự kiện nếu chưa nhập
        $year  = now()->year;
        $semester = strtoupper(str_replace(' ', '', $data['semester'] ?? ''));
        if (in_array($semester, ['HKI', 'HKII', 'HKHE'])) {
            $prefix = "{$year}{$semester}";
            $count = Event::where('event_code', 'like', "{$prefix}%")->count() + 1;
            $data['event_code'] = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        // ✅ Lưu sự kiện
        $event = Event::create($data);

        // ✅ Gửi thông báo nội bộ
        // DB::table('notifications')->insert([
        //     'user_id' => null,
        //     'event_id' => $event->event_id, // ✅ thêm dòng này
        //     'title' => '🎉 Sự kiện mới: ' . $event->event_name,
        //     'message' => 'Giáo viên vừa tạo sự kiện "' . $event->event_name . '". Hãy xem chi tiết và đăng ký tham gia nhé!',
        //     'type' => 'new_event',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);


        // ✅ Gửi mail đến sinh viên
        // $students = DB::table('users')->where('role', 'student')->whereNotNull('email')->pluck('email');
        // $registerLink = route('events.show', $event->event_id);

        // foreach ($students as $email) {
        //     Mail::send('emails.new_event', [
        //         'event' => $event,
        //         'registerLink' => $registerLink,
        //     ], function ($message) use ($email, $event) {
        //         $message->to($email)
        //             ->subject("[THÔNG BÁO] Sự kiện mới “{$event->event_name}” – Đăng ký tham gia ngay!");
        //     });
        // }

        return redirect()->route('events.index')->with('status', 'Tạo sự kiện thành công cần huy động để gửi thông báo cho sinh viên.');
    }

    public function edit(Event $event)
{
    $managers = User::where('role', 'admin')
        ->orderBy('full_name')
        ->get(['user_id', 'full_name', 'username']);

    $classes = DB::table('users')
        ->whereNotNull('class')
        ->distinct()
        ->orderBy('class')
        ->pluck('class');

    $faculties = [
        'Công nghệ thông tin',
        'Kế toán',
        'Ngân hàng',
        'Tài chính',
        'Chất lượng cao',
        'Khác',
        'Tất cả'
    ];

    return view('events.form', [
        'event' => $event,
        'managers' => $managers,
        'mode' => 'edit',
        'classes' => $classes,
        'faculties' => $faculties,
    ]);
}

    public function update(Request $request, Event $event)
    {
        $data = $this->validated($request, $event);

        // ✅ Upload ảnh mới nếu có
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($event->image_url && str_contains($event->image_url, 'storage/')) {
                $oldPath = str_replace(asset('storage') . '/', '', $event->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('events', 'public');
            $data['image_url'] = asset('storage/' . $path);
        }

        $event->fill($data)->save();

        return redirect()->route('events.index')->with('status', 'Cập nhật sự kiện thành công.');
    }

    public function destroy(Event $event)
    {
        // ✅ Xóa ảnh cũ khi xóa sự kiện
        if ($event->image_url && str_contains($event->image_url, 'storage/')) {
            $oldPath = str_replace(asset('storage') . '/', '', $event->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $event->delete();
        return back()->with('status', 'Đã xoá sự kiện.');
    }

    protected function validated(Request $request, ?Event $event): array
    {
        $eventId = $event?->event_id;

        return $request->validate([
            'event_code'       => ['nullable', 'string', 'max:20', Rule::unique('events', 'event_code')->ignore($eventId, 'event_id')],
            'event_name'       => ['required', 'string', 'max:150'],
            'organizer'        => ['nullable', 'string', 'max:100'],
            'manager_id'       => ['nullable', 'integer', 'exists:users,user_id'],
            'level'            => ['nullable', Rule::in(['Cấp trường', 'Cấp khoa', 'Cấp đơn vị'])],
            'semester'         => ['required', Rule::in(['HKI', 'HKII', 'HKHE'])],
            'academic_year'    => ['required', 'string', 'max:15'],
            'start_time'       => ['required', 'date'],
            'end_time'         => ['required', 'date', 'after:start_time'],
            'location'         => ['nullable', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'max_participants' => ['required', 'integer', 'min:1'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:start_time'],
            'image'            => ['nullable', 'image', 'max:2048'], // ✅ validate ảnh
            'target_faculty'   => ['nullable', 'string', 'max:100'],
            'target_class'     => ['nullable', 'string', 'max:100'],
            'target_gender'    => ['nullable', Rule::in(['Nam', 'Nữ', 'Tất cả'])],
        ], [
            'event_name.required' => 'Tên sự kiện là bắt buộc.',
            'semester.required' => 'Vui lòng chọn học kỳ.',
            'academic_year.required' => 'Vui lòng nhập năm học.',
            'max_participants.required' => 'Vui lòng nhập số lượng sinh viên tối đa.',
            'max_participants.min' => 'Số lượng phải lớn hơn 0.',
            'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'registration_deadline.before_or_equal' => '⏰ Hạn đăng ký phải trước hoặc bằng thời gian bắt đầu sự kiện.',
            'image.image' => 'Tệp tải lên phải là ảnh.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
            'target_faculty.max' => 'Tên khoa không được vượt quá 100 ký tự.',
            'target_class.max'   => 'Tên lớp không được vượt quá 100 ký tự.',
            'target_gender.in'   => 'Giới tính không hợp lệ.',
        ]);
    }

public function recruit(Event $event)
{
    if ($event->is_recruiting) {
        return back()->with('error', 'Sự kiện này đã được huy động trước đó.');
    }

    // ✅ Lọc sinh viên đúng đối tượng áp dụng
    $studentsQuery = DB::table('users')
        ->where('role', 'student')
        ->whereNotNull('email');

    if (!empty($event->target_faculty) && $event->target_faculty !== 'Tất cả') {
        $studentsQuery->where('faculty', $event->target_faculty);
    }

    if (!empty($event->target_class)) {
        $studentsQuery->where('class', $event->target_class);
    }

    if (!empty($event->target_gender) && $event->target_gender !== 'Tất cả') {
        $studentsQuery->where('gender', $event->target_gender);
    }

    $students = $studentsQuery->select('user_id', 'email', 'full_name')->get();

    // ✅ Kiểm tra trước khi cập nhật trạng thái
    if ($students->isEmpty()) {
        return back()->with('error', '❌ Không tìm thấy sinh viên phù hợp với đối tượng áp dụng. Sự kiện chưa được huy động.');
    }

    // ✅ Chỉ khi có sinh viên phù hợp mới cập nhật trạng thái
    $event->update(['is_recruiting' => true]);

    // ✅ Ghi thông báo cho đúng nhóm
    $notifications = [];
    foreach ($students as $student) {
        $notifications[] = [
            'user_id' => $student->user_id,
            'event_id' => $event->event_id,
            'title' => '📢 Huy động tham gia sự kiện: ' . $event->event_name,
            'message' => 'Một sự kiện mới dành cho bạn: "' . $event->event_name . '" diễn ra tại ' . ($event->location ?? '...'),
            'type' => 'recruit_event',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    DB::table('notifications')->insert($notifications);

    // ✅ Gửi mail
    $registerLink = route('events.show', $event->event_id);
    foreach ($students as $student) {
        Mail::send('emails.new_event', [
            'event' => $event,
            'registerLink' => $registerLink,
            'studentName' => $student->full_name,
        ], function ($message) use ($student, $event) {
            $message->to($student->email)
                ->subject("[THÔNG BÁO] Sự kiện “{$event->event_name}” sắp diễn ra – Đăng ký ngay hôm nay!");
        });
    }

    return back()->with('status', '📧 Đã gửi huy động và thông báo đến đúng sinh viên phù hợp với đối tượng áp dụng.');
}


  public function registrations(Request $request, Event $event)
{
    try {
        $class = $request->get('class');
        $faculty = $request->get('faculty');

        $students = DB::table('event_registration as er')
            ->join('users as u', 'u.user_id', '=', 'er.user_id')
            ->where('er.event_id', $event->event_id)
            ->when($class, fn($q) => $q->where('u.class', $class))
            ->when($faculty, fn($q) => $q->where('u.faculty', $faculty))
            ->select(
                'u.user_id',
                'u.username as msv',
                'u.full_name',
                'u.class',
                'u.faculty',
                'er.register_date'
            )
            ->orderBy('u.faculty')
            ->orderBy('u.class')
            ->orderBy('u.full_name')
            ->get();

        // Trả về cả danh sách distinct để lọc
        $classes = DB::table('users')->distinct()->pluck('class');
        $faculties = DB::table('users')->distinct()->pluck('faculty');

        return response()->json([
            'students' => $students,
            'classes' => $classes,
            'faculties' => $faculties,
        ]);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}




}
