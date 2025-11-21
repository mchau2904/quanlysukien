<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Event;
use App\Models\User;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // 📋 Danh sách phản hồi
    public function index()
    {
        $feedbacks = Feedback::with(['event', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('feedbacks.index', compact('feedbacks'));
    }

    // ➕ Form thêm mới
    public function create()
    {
        $events = Event::orderBy('event_name')->get();
        $users = User::orderBy('full_name')->get();

        return view('feedbacks.create', compact('events', 'users'));
    }

    // 💾 Lưu phản hồi mới
    public function store(FeedbackRequest $request)
    {
        Feedback::create($request->validated());
        return redirect()->route('feedbacks.index')->with('success', 'Thêm phản hồi thành công!');
    }

    // 👁️ Xem chi tiết phản hồi
    public function show($id)
    {
        $feedback = Feedback::with(['event', 'user'])->findOrFail($id);
        return view('feedbacks.show', compact('feedback'));
    }

    // ✏️ Form chỉnh sửa
    public function edit($id)
    {
        $feedback = Feedback::findOrFail($id);
        $events = Event::orderBy('event_name')->get();
        $users = User::orderBy('full_name')->get();

        return view('feedbacks.edit', compact('feedback', 'events', 'users'));
    }

    // 🔄 Cập nhật phản hồi
    public function update(FeedbackRequest $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update($request->validated());

        return redirect()->route('feedbacks.index')->with('success', 'Cập nhật phản hồi thành công!');
    }

    // ❌ Xóa phản hồi
    public function destroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('feedbacks.index')->with('success', 'Xóa phản hồi thành công!');
    }
}
