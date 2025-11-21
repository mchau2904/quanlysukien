<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Đăng nhập</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
        }

        /* Bên trái: ảnh + overlay */
        .left {
            flex: 1;
            background: url("{{ asset('img/login.jpg') }}"); /* đổi path ảnh tại đây */
            background-size: 100%;
            background-position: center;
        }

        /* Bên phải: form đăng nhập */
        .right {
            flex: 0.45;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            padding: 40px;
        }

        .form-container {
            width: 100%;
            max-width: 360px;
        }

        h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #222;
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 6px;
            color: #555;
        }

        .input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
            font-size: 14px;
            outline: none;
            color: #333;
        }

        .input::placeholder {
            color: #bbb;
        }

        .captcha-box {
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed #ddd;
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 10px;
            color: #253B80;
            background: #f9fafb;
            user-select: none;
        }

        .captcha-actions {
            text-align: right;
            margin-top: 6px;
        }

        .captcha-actions button {
            background: none;
            border: none;
            color: #a243ff;
            font-size: 13px;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(90deg, #ff4fd8, #a243ff);
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .error {
            color: #b91c1c;
            font-size: 12px;
            margin-top: 4px;
        }

        .footer-note {
            text-align: right;
            font-size: 14px;
            color: #555;
            margin-top: 15px;
        }

        .footer-note a {
            color: #555;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="left"></div>

    <div class="right">
        <div class="form-container">
            <h2>Đăng nhập</h2>

            <form method="POST" action="{{ route('login.do') }}" id="loginForm">
                @csrf

                {{-- Username --}}
                <div class="field">
                    <label for="username">Tên đăng nhập</label>
                    <input class="input" id="username" name="username" type="text" style="padding-left: 10px; border-radius: 8px;"
                           placeholder="Tên đăng nhập..." value="{{ old('username') }}" required>
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label for="password">Mật khẩu</label>
                    <input class="input" id="password" name="password" type="password"
                           placeholder="************" style="padding-left: 10px; border-radius: 8px;" required>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Captcha --}}
                <div class="field">
                    <label>Mã bảo vệ</label>
                    <div id="text-captcha-box" class="captcha-box">
                        {{ isset($captchaCode) ? implode(' ', str_split($captchaCode)) : '' }}
                    </div>
                    <div class="captcha-actions">
                        <button type="button" id="refreshTextCaptcha">Tải mã mới</button>
                    </div>
                    <input class="input" type="text" name="captcha" id="captchaInput"
                           placeholder="Nhập mã bảo vệ..." autocomplete="off" required>
                    @error('captcha')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Lỗi đăng nhập --}}
                @if ($errors->has('auth'))
                    <div class="error">{{ $errors->first('auth') }}</div>
                @endif

                <button type="submit" class="btn">Đăng nhập</button>
            </form>

         
        </div>
    </div>

    <script>
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
