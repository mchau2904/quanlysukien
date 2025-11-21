<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        // ✅ Chặn người không phải admin ID=1
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user || $user->user_id != 1) {
                abort(403, '🚫 Bạn không có quyền truy cập trang này.');
            }
            return $next($request);
        });
    }

    // ✅ Danh sách cán bộ
    public function index(Request $request)
    {
        $search = trim($request->input('q'));

        $admins = User::where('role', 'admin')
            ->where('user_id', '!=', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('user_id')
            ->paginate(10)
            ->appends(['q' => $search]);

        return view('admins.index', compact('admins'));
    }



    // ✅ Form thêm
    public function create()
    {
        return view('admins.create');
    }

    // ✅ Lưu cán bộ mới
    public function store(Request $request)
    {
        $validator = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
        ], [
            'username.unique' => 'Trùng mã cán bộ.',
            'email.unique' => 'Email đã tồn tại.',
        ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 0,
        //         'errors' => $validator->errors()
        //     ], 422);
        // }     

        // ✅ Nếu không nhập mật khẩu → dùng mặc định 12345678
        $password = $request->input('password') ?: '12345678';

        $admin = \App\Models\User::create([
            'username' => $request->input('username'),
            'password' => sha1($password), // mã hóa SHA1 để đồng nhất với model User
            'full_name' => $request->input('full_name'),
            'dob' => $request->input('dob'),
            'gender' => $request->input('gender'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'faculty' => $request->input('faculty'),
            'role' => 'admin',
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admins.index')
            ->with('status', '✅ Đã thêm cán bộ thành công! Mật khẩu mặc định: 12345678');
    }


    // ✅ Form chỉnh sửa
    public function edit($id)
    {
        $admin = User::findOrFail($id);
        return view('admins.edit', compact('admin'));
    }

    // ✅ Cập nhật cán bộ
    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $data = $request->validate([
            'full_name' => 'required|string|max:100',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:users,email,' . $id . ',user_id',
            'faculty' => 'nullable|string|max:100',
        ]);

        $admin->update($data);
        return redirect()->route('admins.index')->with('status', '✅ Cập nhật thông tin thành công!');
    }

    // ✅ Xóa cán bộ
    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        if ($admin->user_id == 1) {
            return back()->withErrors(['error' => '❌ Không thể xoá tài khoản System Admin.']);
        }

        $admin->delete();
        return redirect()->route('admins.index')->with('status', '🗑️ Đã xoá cán bộ.');
    }
}
