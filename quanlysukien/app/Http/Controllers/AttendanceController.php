<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Hiển thị form điểm danh: GET /attendance?event_id=...
     */
    public function showForm(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        $eventId = (int) $request->query('event_id');
        if (!$eventId) {
            return redirect()->route('events.index')->with('error', 'Thiếu mã sự kiện.');
        }

        $event = Event::find($eventId);
        if (!$event) {
            return redirect()->route('events.index')->with('error', 'Sự kiện không tồn tại.');
        }

        // ✅ Kiểm tra nếu đã điểm danh (ưu tiên kiểm tra trước khi chặn thời gian)
        $attendance = DB::table('attendance')
            ->where('event_id', $event->event_id)
            ->where('user_id', $user->user_id)
            ->first();

        if ($attendance) {
            // Đã điểm danh → cho phép xem lại thông tin, kể cả khi sự kiện kết thúc
            return view('attendance.form', [
                'event' => $event,
                'alreadyChecked' => true,
                'attendance' => $attendance,
            ]);
        }

        // 🔒 Nếu chưa điểm danh và sự kiện đã kết thúc → chặn
        if (now()->greaterThan($event->end_time)) {
            return redirect()->route('registrations.mine')->with('error', 'Sự kiện đã kết thúc, không thể điểm danh.');
        }

        // ✅ Kiểm tra đã đăng ký chưa
        $hasReg = EventRegistration::where('event_id', $event->event_id)
            ->where('user_id', $user->user_id)
            ->exists();
        if (!$hasReg) {
            return redirect()->route('events.index')->with('error', 'Bạn chưa đăng ký sự kiện này.');
        }

        // Cho phép điểm danh sớm 10 phút & trong suốt thời gian diễn ra
        $check = $this->checkWindow($event, 10);
        if ($check !== 'ok') {
            return redirect()->route('registrations.mine')->with('error', $check);
        }

        return view('attendance.form', [
            'event' => $event,
            'alreadyChecked' => false
        ]);
    }




    /**
     * Lưu điểm danh (ảnh) – POST /attendance
     * Trả JSON khi là AJAX/fetch, hoặc redirect nếu form thường
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->fail($request, 'Vui lòng đăng nhập.', 401, route('login'));
        }

        // Validate
        $validated = $request->validate([
            'event_id' => 'required|integer|exists:events,event_id',
            'photo'    => 'required|image|mimes:jpeg,png,jpg|max:10240',
        ], [], [
            'event_id' => 'sự kiện',
            'photo'    => 'ảnh điểm danh',
        ]);

        $event = Event::find($validated['event_id']);
        // ✅ Kiểm tra đã điểm danh rồi chưa
        $already = DB::table('attendance')
            ->where('event_id', $event->event_id)
            ->where('user_id', $user->user_id)
            ->exists();

        if ($already) {
            return $this->fail($request, 'Bạn đã điểm danh sự kiện này rồi.', 403, route('registrations.mine'));
        }
        // Bắt buộc phải có đăng ký trước
        $hasReg = EventRegistration::where('event_id', $event->event_id)
            ->where('user_id', $user->user_id)
            ->exists();
        if (!$hasReg) {
            return $this->fail($request, 'Bạn chưa đăng ký sự kiện này.', 403, route('events.index'));
        }

        // Kiểm tra khung giờ điểm danh ±10 phút so với start_time (đề phòng người dùng bypass bước trước)
        $check = $this->checkWindow($event, 10);
        if ($check !== 'ok') {
            return $this->fail($request, $check, 422, route('registrations.mine'));
        }

        // Lấy IP thực (ưu tiên LAN khi local)
        $clientIp = $request->ip();
        if ($clientIp === '127.0.0.1' || $clientIp === '::1') {
            $allIps = gethostbynamel(gethostname());
            $clientIp = collect($allIps)->first(fn($ip) => preg_match('/^(192\.168\.|10\.|14\.|172\.)/', $ip)) ?? '127.0.0.1';

            Log::info('ATTENDANCE IP DEBUG', [
                'ip_request' => $request->ip(),
                'ip_real'    => $clientIp,
                'all_local_ips' => gethostbynamel(gethostname()) ?: [],
                'hostname'   => gethostname(),
            ]);

            // Nếu cấu hình dải IP, kiểm tra
            if (!$this->ipAllowed($clientIp)) {
                return $this->fail($request, 'Vui lòng kết nối mạng của trường để điểm danh.', 403);
            }

            // Lưu ảnh
            $file      = $validated['photo'];
            $timestamp = Carbon::now()->format('Ymd_His');
            $filename  = "{$user->user_id}_{$timestamp}." . $file->getClientOriginalExtension();
            $pathDir   = config('attendance.storage_folder', 'attendance') . "/user_{$user->user_id}";
            // $stored    = $file->storeAs($pathDir, $filename, 'public');
            // $imageUrl  = Storage::disk('public')->url($stored);

            $stored    = $file->storeAs($pathDir, $filename, 'public');
            $imageUrl  = asset('storage/' . $stored);

            // Ghi DB (attendance_id là AUTO_INCREMENT)
            DB::table('attendance')->insert([
                'event_id'      => $event->event_id,
                'user_id'       => $user->user_id,
                'checkin_time'  => now(),
                'checkout_time' => null,
                'qr_code'       => null,
                'location'      => null,
                'status'        => 'Có mặt',
                'image_url'     => $imageUrl,
            ]);

            return $this->ok($request, [
                'message'   => 'Điểm danh thành công!',
                'image_url' => $imageUrl,
                'ip'        => $clientIp,
            ], route('registrations.mine'));
        }
    }

    /* ===================== Helpers ===================== */

    /**
     * Cho phép điểm danh trong khoảng:
     * - Trước giờ bắt đầu tối đa $minutes phút
     * - Trong toàn bộ thời gian diễn ra sự kiện
     */
    protected function checkWindow(Event $event, int $minutes = 10): string
    {
        $start       = $event->start_time instanceof Carbon ? $event->start_time : Carbon::parse($event->start_time);
        $end         = $event->end_time instanceof Carbon ? $event->end_time : Carbon::parse($event->end_time);

        // Cho phép điểm danh từ [start - minutes, end]
        $windowStart = $start->copy()->subMinutes($minutes);
        $windowEnd   = $end->copy();
        $now         = now();

        if ($now->lt($windowStart)) {
            return "Chưa đến giờ điểm danh";
        }

        if ($now->gt($windowEnd)) {
            return "Sự kiện đã kết thúc, không thể điểm danh nữa.";
        }

        return 'ok';
    }


    /**
     * Kiểm tra IP có nằm trong danh sách mạng trường cho phép không
     */
    protected function ipAllowed(string $ip): bool
    {
        $cidrs = config('attendance.allowed_ip_cidrs', []); // ví dụ: ['192.168.0.0/16','10.0.0.0/8']
        if (empty($cidrs)) {
            // Nếu chưa cấu hình dải IP => cho qua
            return true;
        }
        foreach ($cidrs as $cidr) {
            if ($this->ipInRange($ip, $cidr)) return true;
        }
        return false;
    }

    /**
     * Kiểm tra 1 IP có thuộc dải CIDR nào không
     */
    protected function ipInRange(string $ip, string $cidrOrIp): bool
    {
        if (strpos($cidrOrIp, '/') !== false) {
            [$subnet, $mask] = explode('/', $cidrOrIp);
            $ipDec = ip2long($ip);
            $subnetDec = ip2long($subnet);
            $maskDec = -1 << (32 - (int)$mask);
            return ($ipDec & $maskDec) === ($subnetDec & $maskDec);
        }
        return $ip === $cidrOrIp;
    }

    /**
     * Trả về JSON khi AJAX, hoặc redirect+flash khi form thường (thành công)
     */
    protected function ok(Request $request, array $payload, ?string $redirectRoute = null)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($payload);
        }
        return $redirectRoute
            ? redirect()->to($redirectRoute)->with('status', $payload['message'] ?? 'Thành công')
            : back()->with('status', $payload['message'] ?? 'Thành công');
    }

    /**
     * Trả về JSON khi AJAX, hoặc redirect+flash khi form thường (thất bại)
     */
    protected function fail(Request $request, string $message, int $status = 400, ?string $redirectRoute = null)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['error' => $message], $status);
        }
        return $redirectRoute
            ? redirect()->to($redirectRoute)->with('error', $message)
            : back()->with('error', $message);
    }
}
