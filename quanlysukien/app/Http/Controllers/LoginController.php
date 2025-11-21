<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function show(Request $request)
    {
        $code = $this->generateCode();
        $request->session()->put('captcha_code', $code);
        return view('auth.login', ['captchaCode' => $code]);
    }

    public function refreshCaptcha(Request $request)
    {
        $code = $this->generateCode();
        $request->session()->put('captcha_code', $code);
        return response()->json(['code' => $code]);
    }

    protected function generateCode($len = 5)
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // bỏ I,O,1,0
        $out = '';
        for ($i = 0; $i < $len; $i++) {
            $out .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }
        return $out;
    }

    public function authenticate(Request $request)
{
    // 1️⃣ Validate đầu vào
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
        'captcha'  => 'required|string',
    ]);

    // 2️⃣ Kiểm tra mã captcha
    if (strtoupper($request->input('captcha')) !== strtoupper($request->session()->get('captcha_code'))) {
        // Sinh lại mã captcha cho lần sau
        $request->session()->put('captcha_code', $this->generateCode());
        return back()->withErrors(['captcha' => 'Mã bảo vệ không đúng.'])->withInput();
    }

    // 3️⃣ Tìm user theo username
    $user = DB::table('users')->where('username', $request->username)->first();
    if (!$user) {
        $request->session()->put('captcha_code', $this->generateCode());
        return back()->withErrors(['auth' => 'Thông tin đăng nhập không chính xác.'])->withInput();
    }

    // 4️⃣ Kiểm tra mật khẩu (dùng Hash::check nếu DB lưu bcrypt, 
    //    hoặc vẫn giữ SHA1 nếu hệ thống cũ)
    $isPasswordMatch = false;

    // Nếu bạn đã chuyển sang bcrypt:
    // $isPasswordMatch = Hash::check($request->password, $user->password);

    // Nếu DB hiện vẫn lưu SHA1, giữ tạm:
    $isPasswordMatch = hash_equals($user->password, sha1($request->password));

    if (!$isPasswordMatch) {
        $request->session()->put('captcha_code', $this->generateCode());
        return back()->withErrors(['auth' => 'Thông tin đăng nhập không chính xác.'])->withInput();
    }

    // 5️⃣ Kiểm tra vai trò người dùng trong DB (phòng trường hợp tài khoản lỗi)
    if (!in_array($user->role, ['admin', 'student'], true)) {
        return back()->withErrors(['auth' => 'Tài khoản chưa được gán vai trò hợp lệ.'])->withInput();
    }

    // 6️⃣ Đăng nhập người dùng qua model (Auth chuẩn Laravel)
    $userModel = \App\Models\User::find($user->user_id);
    Auth::login($userModel);
    $request->session()->regenerate();
    $request->session()->forget('captcha_code');

    // 7️⃣ Điều hướng theo vai trò thật từ DB
    if ($user->role === 'admin') {
        return redirect()->intended(route('home', absolute: false) ?? '/');
    }

    return redirect()->intended(route('home', absolute: false) ?? '/');
}
}
