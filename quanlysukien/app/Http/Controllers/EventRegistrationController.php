<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventRegistrationController extends Controller
{
    /**
     * Window cho phép điểm danh: trước & sau giờ bắt đầu (phút)
     */
    private const CHECKIN_WINDOW_MINUTES = 10;

    /**
     * POST /events/{event}/register
     */
    public function store(Event $event)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login.show')->with('error', 'Vui lòng đăng nhập.');
        }

        if ($user->role !== 'student') {
            return back()->with('error', 'Chỉ sinh viên mới được đăng ký.')->withInput();
        }
        // 1️⃣ Không cho đăng ký trùng
        $already = EventRegistration::where('event_id', $event->event_id)
            ->where('user_id', $user->user_id)
            ->exists();

        if ($already) {
            return redirect()->route('registrations.mine')
                ->with('status', 'Bạn đã đăng ký sự kiện này trước đó.');
        }
        // 2️⃣ Không cho đăng ký sự kiện đã kết thúc
        if (now()->greaterThan($event->end_time)) {
            return back()->with('error', 'Sự kiện đã kết thúc, không thể đăng ký.');
        }
        if ($event->registration_deadline && now()->greaterThan($event->registration_deadline)) {
            return back()->with('error', 'Hạn đăng ký sự kiện đã kết thúc.');
        }

       

        // 3️⃣ Kiểm tra số lượng tối đa người tham gia
        if (!is_null($event->max_participants)) {
            $current = EventRegistration::where('event_id', $event->event_id)->count();
            if ($current >= $event->max_participants) {
                return back()->with('error', 'Sự kiện đã đủ số lượng tham gia, không thể đăng ký thêm.');
            }
        }

        // 4️⃣ Kiểm tra trùng thời gian với sự kiện khác mà sinh viên đã đăng ký
        $conflict = EventRegistration::join('events', 'events.event_id', '=', 'event_registration.event_id')
            ->where('event_registration.user_id', $user->user_id)
            ->where(function ($q) use ($event) {
                $q->whereBetween('events.start_time', [$event->start_time, $event->end_time])
                    ->orWhereBetween('events.end_time', [$event->start_time, $event->end_time])
                    ->orWhere(function ($sub) use ($event) {
                        $sub->where('events.start_time', '<=', $event->start_time)
                            ->where('events.end_time', '>=', $event->end_time);
                    });
            })
            ->select('events.event_name', 'events.start_time', 'events.end_time')
            ->first();


        if ($conflict) {
            return back()->with('error', "Bạn đã đăng ký sự kiện khác trùng thời gian: 
        {$conflict->event_name} ({$conflict->start_time} - {$conflict->end_time}).");
        }

        // 5️⃣ Nếu không trùng, tạo bản ghi đăng ký
        EventRegistration::create([
            'event_id'      => $event->event_id,
            'user_id'       => $user->user_id,
            'status'        => 'Đã đăng ký',
            'register_date' => now(),
        ]);

        // 6️⃣ Quay lại trang "Sự kiện của tôi"
        return redirect()->route('registrations.mine')
            ->with('status', "Bạn đã đăng ký thành công sự kiện này!");
    }


    /**
     * GET /my/events
     */
    public function myEvents(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login.show')->with('error', 'Vui lòng đăng nhập.');
        }

        // 🔍 Lấy từ khóa tìm kiếm
        $q = trim($request->get('q', ''));

        $regs = EventRegistration::with('event')
            ->where('user_id', $user->user_id)
            ->when($q !== '', function ($query) use ($q) {
                // Lọc theo tên sự kiện
                $query->whereHas('event', function ($sub) use ($q) {
                    $sub->where('event_name', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('register_date')
            ->get();

        return view('events.my', compact('regs', 'q'));
    }

    /**
     * GET /events/{event}/checkin
     * Chỉ cho vào form điểm danh nếu now ∈ [start-10’, start+10’]
     */
    public function checkin(Event $event)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login.show')->with('error', 'Vui lòng đăng nhập.');
        }

        // Phải đăng ký rồi mới được điểm danh
        $hasReg = EventRegistration::where('event_id', $event->event_id)
            ->where('user_id', $user->user_id)
            ->exists();

        if (!$hasReg) {
            return back()->with('error', 'Bạn chưa đăng ký sự kiện này.');
        }

        // Tính cửa sổ điểm danh
        $start = $event->start_time->copy(); // cast datetime từ Model Event
        $windowStart = $start->copy()->subMinutes(self::CHECKIN_WINDOW_MINUTES);
        $windowEnd   = $start->copy()->addMinutes(self::CHECKIN_WINDOW_MINUTES);
        $now = now();

        if ($now->lt($windowStart)) {
            return back()->with('error', 'Chưa đến giờ điểm danh ' . self::CHECKIN_WINDOW_MINUTES . ' phút).');
        }

        if ($now->gt($windowEnd)) {
            return back()->with('error', 'Bạn đã đến muộn giờ điểm danh (quá ' . self::CHECKIN_WINDOW_MINUTES . ' phút sau giờ bắt đầu).');
        }

        // Hợp lệ -> chuyển sang trang điểm danh
        return redirect()->route('attendance.form', ['event_id' => $event->event_id]);
    }
}
