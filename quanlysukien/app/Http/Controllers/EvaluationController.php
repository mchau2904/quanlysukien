<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Event;
use App\Models\User;
use App\Http\Requests\EvaluationRequest;
use App\Models\FeedbackReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    
public function listEvents(Request $request)
{
    $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString();
    $q = trim((string) $request->get('q', ''));

    $events = DB::table('events')
        ->leftJoin('users', 'users.user_id', '=', 'events.manager_id')
        ->select(
            'events.*',
            'users.full_name as manager_name',
            DB::raw("
                CASE
                    WHEN EXISTS (
                        SELECT 1
                        FROM attendance a
                        WHERE a.event_id = events.event_id
                    )
                    AND NOT EXISTS (
                        SELECT 1
                        FROM attendance a2
                        WHERE a2.event_id = events.event_id
                        AND a2.user_id NOT IN (
                            SELECT e.user_id
                            FROM evaluations e
                            WHERE e.event_id = events.event_id
                        )
                    )
                    THEN 'Đã đánh giá'
                    ELSE 'Chưa đánh giá'
                END as status
            ")
        )
        ->when($q, fn($q2) => $q2->where(function($w) use ($q) {
            $w->where('events.event_code', 'like', "%$q%")
            ->orWhere('events.event_name', 'like', "%$q%");
        }))
        ->whereRaw('events.end_time < ?', [$now]) // chỉ lấy sự kiện đã kết thúc
        ->orderByRaw("
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM attendance a
                    WHERE a.event_id = events.event_id
                )
                AND NOT EXISTS (
                    SELECT 1 FROM attendance a2
                    WHERE a2.event_id = events.event_id
                    AND a2.user_id NOT IN (
                        SELECT e.user_id
                        FROM evaluations e
                        WHERE e.event_id = events.event_id
                    )
                )
                THEN 1 ELSE 0
            END ASC
        ")
        ->orderByDesc('events.start_time')
        ->get();

    return view('evaluation.list', compact('events', 'q'));
}




 public function index($event_id, Request $request)
{
    $search = $request->input('search');
    $status = $request->input('status'); // ✅ lọc theo điểm danh

    $students = DB::table('event_registration as er')
        ->join('users as u', 'u.user_id', '=', 'er.user_id')
        ->join('events as e', 'e.event_id', '=', 'er.event_id')
        ->leftJoin('attendance as a', function ($join) {
            $join->on('a.event_id', '=', 'er.event_id')
                 ->on('a.user_id', '=', 'er.user_id');
        })
        ->leftJoin('evaluations as ev', function ($join) {
            $join->on('ev.event_id', '=', 'er.event_id')
                 ->on('ev.user_id', '=', 'er.user_id');
        })
        ->select(
            'u.user_id', 'u.username', 'u.full_name',
            'e.start_time', 'e.end_time',
            'a.checkin_time', 'a.image_url',
            DB::raw('COALESCE(ev.score, 0) as score')
        )
        ->where('er.event_id', $event_id)
        ->when($search, function ($query) use ($search) {
            $query->where('u.full_name', 'like', "%$search%")
                  ->orWhere('u.username', 'like', "%$search%");
        })
        ->when($status === 'checked', function ($query) {
            $query->whereNotNull('a.checkin_time');
        })
        ->when($status === 'unchecked', function ($query) {
            $query->whereNull('a.checkin_time');
        })
        ->orderBy('u.full_name')
        ->get();

    $event = DB::table('events')->where('event_id', $event_id)->first();

    return view('evaluation.index', compact('students', 'event', 'search'));
}




    // Lưu đánh giá
    public function store(Request $request)
    {
        $data = $request->input('evaluations');

        foreach ($data as $eval) {
            DB::table('evaluations')->updateOrInsert(
                [
                    'event_id' => $eval['event_id'],
                    'user_id' => $eval['user_id']
                ],
                [
                    'score' => $eval['score'],
                    'created_at' => now()
                ]
            );
        }

        return redirect()->back()->with('success', 'Đã lưu đánh giá thành công!');
    }

    // Gửi phản hồi riêng lẻ
    public function feedback(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|integer',
            'user_id' => 'required|integer',
            'content' => 'required|string|max:500'
        ]);

        DB::table('feedbacks')->updateOrInsert(
            [
                'event_id' => $validated['event_id'],
                'user_id' => $validated['user_id']
            ],
            [
                'content' => $validated['content'],
                'created_at' => now()
            ]
        );

        // 🔹 Thêm thông báo cho sinh viên
        $event = DB::table('events')->where('event_id', $validated['event_id'])->first();
        $teacher = auth()->user();

        DB::table('notifications')->insert([
            'user_id'   => $validated['user_id'], // người nhận là sinh viên
            'event_id'  => $validated['event_id'], // sự kiện liên quan
            'title'     => 'Phản hồi mới từ giáo viên',
            'message'   => "{$teacher->full_name} đã phản hồi bạn trong sự kiện '{$event->event_name}'.",
            'type'      => 'teacher_feedback',
            'is_read'   => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Phản hồi đã được gửi!']);
    }

    public function show($event_id)
    {
        // 1️⃣ Lấy thông tin sự kiện
        $event = DB::table('events')->where('event_id', $event_id)->first();
        if (!$event) {
            return redirect()->route('registrations.mine')->with('error', 'Không tìm thấy sự kiện.');
        }

        // 2️⃣ Kiểm tra sự kiện đã kết thúc chưa
        if (now()->lt($event->end_time)) {
            return redirect()->route('registrations.mine')->with('error', 'Sự kiện chưa kết thúc, chưa thể xem đánh giá.');
        }

        // 3️⃣ Lấy danh sách điểm + phản hồi của giáo viên
        $evaluations = DB::table('evaluations as ev')
            ->join('users as u', 'u.user_id', '=', 'ev.user_id')
            ->leftJoin('feedbacks as f', function ($join) {
                $join->on('f.event_id', '=', 'ev.event_id')
                    ->on('f.user_id', '=', 'ev.user_id');
            })
            ->where('ev.event_id', $event_id)
            ->select(
                'ev.evaluation_id',
                'u.user_id',
                'u.full_name',
                'ev.score',
                'f.feedback_id',
                'f.content as teacher_feedback',
                'ev.created_at'
            )
            ->orderByDesc('ev.created_at')
            ->get();

        // 4️⃣ Lấy phản hồi lại của sinh viên
        foreach ($evaluations as $ev) {
            $ev->replies = DB::table('feedback_replies as fr')
                ->join('users as sender', 'sender.user_id', '=', 'fr.sender_id')
                ->where('fr.feedback_id', $ev->feedback_id)
                ->select('sender.full_name as sender_name', 'fr.content', 'fr.created_at')
                ->orderBy('fr.created_at')
                ->get();
        }

        // 5️⃣ Lấy điểm đánh giá của sinh viên hiện tại
        $studentId = Auth::user()->user_id;

        $studentEvaluation = $evaluations->firstWhere('user_id', $studentId);
        $score = $studentEvaluation->score ?? null;


        return view('evaluation.show', compact('event', 'evaluations', 'score'));
    }


    public function reply(Request $request)
    {
        try {
            $validated = $request->validate([
                'event_id' => 'required|integer|exists:events,event_id',
                'user_id'  => 'required|integer|exists:users,user_id',
                'content'  => 'required|string|max:500',
            ], [
                'content.required' => 'Vui lòng nhập nội dung phản hồi.',
            ]);

            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'Bạn chưa đăng nhập.'], 401);
            }

            $event = Event::find($validated['event_id']);
            if (!$event) {
                return response()->json(['message' => 'Không tìm thấy sự kiện.'], 404);
            }

            // Xác định người nhận phản hồi
            $receiverId = null;
            if ($user->role === 'admin') {
                $receiverId = $validated['user_id']; // GV gửi SV
            } elseif ($user->role === 'student') {
                $receiverId = $event->manager_id ?? 1; // SV gửi GV
            }

            FeedbackReply::create([
                'event_id'   => $validated['event_id'],
                'sender_id'  => $user->user_id,
                'receiver_id' => $receiverId,
                'content'    => $validated['content'],
                'created_at' => now(),
            ]);

            DB::table('notifications')->insert([
                'user_id'   => $receiverId,
                'event_id'  => $validated['event_id'],
                'title'     => 'Phản hồi mới',
                'message'   => ($user->full_name ?? 'Người dùng') . " đã gửi phản hồi trong sự kiện {$event->event_name}",
                'type'      => 'feedback',
                'is_read'   => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => '✅ Phản hồi đã được gửi thành công!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        } catch (\Throwable $e) {
            \Log::error("❌ Reply error: " . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi máy chủ.'], 500);
        }
    }
}
