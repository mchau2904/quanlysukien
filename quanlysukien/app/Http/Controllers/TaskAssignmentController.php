<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignment;
use App\Models\Task;
use App\Models\User;
use App\Http\Requests\TaskAssignmentRequest;

class TaskAssignmentController extends Controller
{
    // 📋 Danh sách
    public function index()
    {
        $assignments = TaskAssignment::with(['task', 'user'])
            ->orderByDesc('assigned_at')
            ->paginate(10);
        return view('task_assignment.index', compact('assignments'));
    }

    // ➕ Form thêm
    public function create()
    {
        $tasks = Task::orderBy('task_name')->get();
        $users = User::orderBy('full_name')->get();
        return view('task_assignment.create', compact('tasks', 'users'));
    }

    // 💾 Lưu mới
    public function store(TaskAssignmentRequest $request)
    {
        TaskAssignment::create($request->validated());
        return redirect()->route('task_assignment.index')->with('success', 'Thêm phân công nhiệm vụ thành công!');
    }

    // 👁️ Xem chi tiết
    public function show($id)
    {
        $assignment = TaskAssignment::with(['task', 'user'])->findOrFail($id);
        return view('task_assignment.show', compact('assignment'));
    }

    // ✏️ Form sửa
    public function edit($id)
    {
        $assignment = TaskAssignment::findOrFail($id);
        $tasks = Task::orderBy('task_name')->get();
        $users = User::orderBy('full_name')->get();
        return view('task_assignment.edit', compact('assignment', 'tasks', 'users'));
    }

    // 🔄 Cập nhật
    public function update(TaskAssignmentRequest $request, $id)
    {
        $assignment = TaskAssignment::findOrFail($id);
        $assignment->update($request->validated());
        return redirect()->route('task_assignment.index')->with('success', 'Cập nhật phân công thành công!');
    }

    // ❌ Xóa
    public function destroy($id)
    {
        $assignment = TaskAssignment::findOrFail($id);
        $assignment->delete();
        return redirect()->route('task_assignment.index')->with('success', 'Xóa phân công thành công!');
    }
}
