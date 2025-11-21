{{-- resources/views/emails/recruit_event.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[Huy động] Tham gia sự kiện “{{ $event->event_name }}”</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f8f9fa; padding:20px;">

    <div style="max-width:650px; margin:auto; background:#fff; border-radius:10px; box-shadow:0 0 8px rgba(0,0,0,0.1); padding:30px;">

        <h2 style="color:#0d6efd; text-align:center; margin-bottom:25px;">
            📢 Huy động tham gia sự kiện “{{ $event->event_name }}”
        </h2>

        <p>Kính gửi Anh/Chị,</p>

        <p>
            Ban tổ chức xin thông báo: sự kiện
            <strong>“{{ $event->event_name }}”</strong>
            sẽ chính thức diễn ra vào
            <strong>{{ \Carbon\Carbon::parse($event->start_time)->format('d/m/Y H:i') }}</strong>
            tại <strong>{{ $event->location ?? 'Chưa cập nhật' }}</strong>.
        </p>

        @if($event->registration_deadline)
        <p><strong>⏰ Hạn đăng ký:</strong> {{ \Carbon\Carbon::parse($event->registration_deadline)->format('d/m/Y H:i') }}</p>
        @endif

        <p>Đây là cơ hội để Anh/Chị tham gia, học hỏi và giao lưu với bạn bè trong các hoạt động ngoại khóa, phát triển kỹ năng cá nhân và tập thể.</p>

        <p style="font-weight:bold;">👉 Hãy đăng ký tham gia ngay hôm nay để không bỏ lỡ!</p>

        <p style="text-align:center; margin:30px 0;">
            <a href="{{ $registerLink }}"
                style="background:#0d6efd; color:#fff; padding:12px 25px; text-decoration:none; border-radius:8px; font-weight:bold;">
                🔗 Đăng ký tham gia ngay
            </a>
        </p>

        <p>Rất mong được đón tiếp Anh/Chị tại sự kiện!</p>

        <p>
            Trân trọng,<br>
            <strong>Ban tổ chức</strong><br>
            Phòng Công tác Sinh viên – Trường Đại học ABC<br>
            📞 Hotline: 0123 456 789<br>
            📧 Email: ctsv@university.edu.vn
        </p>
    </div>

</body>

</html>