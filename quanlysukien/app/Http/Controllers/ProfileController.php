<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = User::find(Auth::id());
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'full_name' => 'required|string|max:100',
            'dob'       => 'nullable|date',
            'gender'    => 'nullable|in:Nam,Nữ,Khác',
            'phone'     => 'nullable|string|max:15',
            'email'     => 'nullable|email|max:100',
            'class'     => 'nullable|string|max:50',
            'faculty'   => 'nullable|string|max:100',
            'password'  => 'nullable|string|min:6', // nếu muốn đổi mật khẩu
        ]);

        // Cập nhật thông tin cơ bản
        $user->fill($request->only(['full_name', 'dob', 'gender', 'phone', 'email', 'class', 'faculty']));

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        // Không cho người dùng tự đổi role ở đây
        // $user->role = $user->role;

        $user->save();

        return redirect('/')->with('status', 'Cập nhật thành công.');
    }
}
