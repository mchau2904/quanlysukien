{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --teal: #0f8b8d;
            --teal-dark: #0c6f71;
            --border: #e6e6e6;
            --text: #333;
            --bg: #f3f4f6;
            --card: #fff;
            --radius: 12px;
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans;
        }

        .wrap {
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 10px 24px rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        /* Header bar - fix vỡ layout bằng padding-top đủ cho nhãn nổi */
        .role-wrap {
            position: relative;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .role-title {
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
            top: 0;
            margin-top: 0;
            background: #fff;
            padding: 4px 12px;
            border: 1px solid var(--border);
            border-radius: 999px;
            color: var(--teal);
            font-weight: 700;
            font-size: 13px;
            white-space: nowrap;
        }

        .role-bar {
            padding: 26px 16px 12px;
            /* chừa chỗ cho role-title */
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
        }

        .radio {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .radio input {
            accent-color: var(--teal);
            width: 16px;
            height: 16px;
        }

        .card-body {
            padding: 20px;
        }

        .field {
            margin-top: 14px;
        }

        .input {
            width: 100%;
            height: 44px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            background: #fff;
            color: var(--text);
        }

        .input::placeholder {
            color: #9ca3af
        }

        /* Captcha hàng riêng (Google widget tự set width) */
        .captcha-area {
            margin-top: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 8px;
            margin-top: 12px;
            background: var(--teal);
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
        }

        .btn[disabled] {
            opacity: .55;
            cursor: not-allowed
        }

        .error {
            color: #b91c1c;
            font-size: 12px;
            margin-top: 6px
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0
        }
    </style>
</head>

<body>
    <div class="wrap">
        {{-- Đổi sang url('/login') để tránh lỗi route nếu bạn chưa đặt name --}}
        <form class="card" method="POST" action="{{ url('/login') }}" id="loginForm">
            @csrf

            {{-- Header vai trò (giống ảnh) --}}
            <div class="role-wrap">
                <div class="role-title">Cổng thông tin đào tạo</div>
                <div class="role-bar">
                    <label class="radio">
                        <input type="radio" name="role" value="student" {{ old('role','student')==='student'?'checked':'' }}>
                        <span>Sinh viên</span>
                    </label>
                    <label class="radio">
                        <input type="radio" name="role" value="admin" {{ old('role')==='admin'?'checked':'' }}>
                        <span>Admin</span>
                    </label>
                </div>
            </div>

            <div class="card-body">
                <div class="field">
                    <label class="sr-only" for="username">Tên đăng nhập</label>
                    <input class="input" id="username" name="username" type="text" placeholder="Tên đăng nhập"
                        value="{{ old('username') }}" required>
                    @error('username') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label class="sr-only" for="password">Mật khẩu</label>
                    <input class="input" id="password" name="password" type="password" placeholder="Mật khẩu" required>
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                {{-- Google reCAPTCHA v2 (checkbox) – chỉ kiểm tra JS, không lưu DB --}}
                <div class="captcha-area">
                    <div id="g-recaptcha" class="g-recaptcha"
                        data-sitekey="{{ env('RECAPTCHA_SITE_KEY', 'YOUR_SITE_KEY_HERE') }}"
                        data-callback="onCaptchaSuccess"
                        data-expired-callback="onCaptchaExpired"></div>
                    @error('captcha') <div class="error">{{ $message }}</div> @enderror
                </div>

                @if ($errors->has('auth'))
                <div class="error">{{ $errors->first('auth') }}</div>
                @endif

                <button type="submit" id="submitBtn" class="btn" disabled>Đăng nhập</button>
            </div>
        </form>
    </div>

    {{-- Google reCAPTCHA v2 script --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Khóa submit tới khi captcha tick
        const submitBtn = document.getElementById('submitBtn');

        function onCaptchaSuccess() {
            submitBtn.disabled = false;
        }

        function onCaptchaExpired() {
            submitBtn.disabled = true;
        }

        // Chặn submit nếu chưa tick (phòng khi JS chạy trước callback)
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (typeof grecaptcha !== 'undefined') {
                const token = grecaptcha.getResponse();
                if (!token) {
                    e.preventDefault();
                    alert('Vui lòng xác nhận mã bảo vệ.');
                }
            }
        });
    </script>
</body>

</html>