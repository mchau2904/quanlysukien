<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    // 🧭 Danh sách người dùng
    public function index(Request $request)
    {
        $role = $request->query('role');
        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('users.index', compact('users', 'role'));
    }

    // ➕ Form thêm mới
    public function create()
    {
        return view('users.create');
    }

    // 💾 Lưu người dùng mới
    public function store(UserRequest $request)
    {
        User::create($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'Tạo người dùng thành công!');
    }

    // ✏️ Form chỉnh sửa
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // 🔄 Cập nhật người dùng
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        // Nếu không nhập password thì giữ nguyên
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Cập nhật người dùng thành công!');
    }

    // ❌ Xóa người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Không thể xóa tài khoản admin!');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Xóa người dùng thành công!');
    }

    // 👁️ Xem chi tiết
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }
    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'role'    => ['required', Rule::in(['student', 'admin'])],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'captcha'], // nếu dùng mews/captcha
        ]);

        // Ví dụ chọn guard theo role (tùy bạn triển khai)
        $guard = $validated['role'] === 'admin' ? 'admin' : 'web';

        if (Auth::guard($guard)->attempt([
            'username' => $validated['username'],
            'password' => $validated['password'],
        ], true)) {
            $request->session()->regenerate();
            return redirect()->intended($validated['role'] === 'admin' ? '/admin' : '/dashboard');
        }

        return back()->withErrors(['auth' => 'Thông tin đăng nhập không chính xác.'])->onlyInput('username', 'role');
    }
}
