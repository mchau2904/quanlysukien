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
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
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

        .header-title {
            text-align: center;
            font-weight: 700;
            font-size: 18px;
            color: var(--teal);
            border-bottom: 1px solid var(--border);
            padding: 20px;
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
            color: #9ca3af;
        }

        .captcha-box {
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--border);
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 10px;
            color: #253B80;
            background: #f9fafb;
            user-select: none;
        }

        .captcha-actions {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 6px;
        }

        .btn {
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 8px;
            margin-top: 16px;
            background: var(--teal);
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
        }

        .btn:hover {
            background: var(--teal-dark);
        }

        .error {
            color: #b91c1c;
            font-size: 12px;
            margin-top: 6px;
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
            border: 0;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <form class="card" method="POST" action="{{ route('login.do') }}" id="loginForm">
            @csrf

            {{-- Tiêu đề --}}
            <div class="header-title">Cổng thông tin đào tạo</div>

            <div class="card-body">
                {{-- Username --}}
                <div class="field">
                    <label class="sr-only" for="username">Tên đăng nhập</label>
                    <input class="input" id="username" name="username" type="text" placeholder="Tên đăng nhập"
                        value="{{ old('username') }}" required>
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label class="sr-only" for="password">Mật khẩu</label>
                    <input class="input" id="password" name="password" type="password" placeholder="Mật khẩu" required>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Captcha --}}
                <div class="field">
                    <div id="text-captcha-box" class="captcha-box">
                        {{ isset($captchaCode) ? implode(' ', str_split($captchaCode)) : '' }}
                    </div>
                    <div class="captcha-actions">
                        <button type="button" id="refreshTextCaptcha"
                            style="background:none;border:none;color:#0f8b8d;cursor:pointer;font-size:13px">
                            Tải mã mới
                        </button>
                    </div>
                    <input class="input" type="text" name="captcha" id="captchaInput" placeholder="Nhập mã bảo vệ"
                        autocomplete="off" required>
                    @error('captcha')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Lỗi đăng nhập --}}
                @if ($errors->has('auth'))
                    <div class="error">{{ $errors->first('auth') }}</div>
                @endif

                <button type="submit" class="btn">Đăng nhập</button>
            </div>
        </form>
    </div>

    <script>
        // Nút "Tải mã mới" -> gọi route refresh để sinh mã mới trong session
        document.getElementById('refreshTextCaptcha').addEventListener('click', async () => {
            try {
                const res = await fetch("{{ route('captcha.refresh') }}", {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                const spaced = (data.code || '').split('').join(' ');
                document.getElementById('text-captcha-box').textContent = spaced;
                document.getElementById('captchaInput').value = '';
            } catch (e) {
                alert('Không tải được mã mới, thử lại!');
            }
        });
    </script>
</body>

</html>
