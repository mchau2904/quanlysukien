<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    // 📋 Danh sách nhiệm vụ
    public function index()
    {
        $tasks = Task::with('event')->orderByDesc('created_at')->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    // ➕ Form thêm
    public function create()
    {
        $events = Event::orderBy('event_name')->get();
        return view('tasks.create', compact('events'));
    }

    // 💾 Lưu mới
    public function store(TaskRequest $request)
    {
        Task::create($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Thêm nhiệm vụ thành công!');
    }

    // 👁️ Xem chi tiết
    public function show($id)
    {
        $task = Task::with('event')->findOrFail($id);
        return view('tasks.show', compact('task'));
    }

    // ✏️ Form chỉnh sửa
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $events = Event::orderBy('event_name')->get();
        return view('tasks.edit', compact('task', 'events'));
    }

    // 🔄 Cập nhật
    public function update(TaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->validated());
        return redirect()->route('tasks.index')->with('success', 'Cập nhật nhiệm vụ thành công!');
    }

    // ❌ Xóa
    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return redirect()->route('tasks.index')->with('success', 'Xóa nhiệm vụ thành công!');
    }
}
