<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang chủ')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.jpg') }}">
    @stack('styles')


</head>

<body class="d-flex flex-column min-vh-100"> {{-- nhớ thêm min-vh-100 để footer dính đáy --}}
    @include('template.header')

    <main class="flex-grow-1">
        <div class="container py-4">
            <div class="page-narrow mx-auto">

                {{-- ⚠️ Hiển thị thông báo flash (status / error) --}}
                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- Nội dung trang --}}
                @yield('content')
            </div>
        </div>
    </main>

    @include('template.footer')
</body>


</html>
<script>
window.firebaseConfig = {
  apiKey: "AIzaSyCIL-3LokWAsvJvNddUGTXhsBiziysS_8A",
  authDomain: "event-feedback-system.firebaseapp.com",
  databaseURL: "https://event-feedback-system-default-rtdb.firebaseio.com",
  projectId: "event-feedback-system",
  storageBucket: "event-feedback-system.firebasestorage.app",
  messagingSenderId: "347348361462",
  appId: "1:347348361462:web:19d1b8b63bb1467f570cd7",
  measurementId: "G-4Z1YR66324"
};
</script>
