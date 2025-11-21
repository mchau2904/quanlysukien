{{-- resources/views/emails/new_event.blade.php --}}
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>[THÔNG BÁO] Sự kiện mới “{{ $event->event_name }}”</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f8f9fa; padding:20px;">

    <div style="max-width:650px; margin:auto; background:#fff; border-radius:10px; box-shadow:0 0 8px rgba(0,0,0,0.1); padding:30px;">

        <h2 style="color:#0d6efd; text-align:center; margin-bottom:25px;">
            🎉 Sự kiện mới “{{ $event->event_name }}” vừa được tạo!
        </h2>

        <p>Kính gửi sinh viên {{ $studentName ?? 'Quý sinh viên' }},</p>

        <p>
            Ban tổ chức xin thông báo: một sự kiện mới mang tên
            <strong>“{{ $event->event_name }}”</strong>
            sẽ diễn ra vào
            <strong>{{ \Carbon\Carbon::parse($event->start_time)->format('d/m/Y H:i') }}</strong>
            tại <strong>{{ $event->location ?? 'Chưa cập nhật' }}</strong>.
        </p>

        <p>
            Sự kiện hứa hẹn mang đến nhiều trải nghiệm, thông tin bổ ích và cơ hội giao lưu, học hỏi dành cho sinh viên.
        </p>

        @if($event->registration_deadline)
        <p><strong>⏰ Hạn đăng ký:</strong> {{ \Carbon\Carbon::parse($event->registration_deadline)->format('d/m/Y H:i') }}</p>
        @endif

        <p style="font-weight:bold;">👉 Hãy theo dõi và đăng ký sớm để đảm bảo suất tham gia!</p>

        <p style="text-align:center; margin:30px 0;">
            <a href="{{ $registerLink }}"
                style="background:#198754; color:#fff; padding:12px 25px; text-decoration:none; border-radius:8px; font-weight:bold;">
                🔗 Xem chi tiết sự kiện
            </a>
        </p>

        <p>Rất mong nhận được sự quan tâm và tham gia của Anh/Chị.</p>

        <p>
            Trân trọng,<br>
            Phòng Công tác Sinh viên – Học viện Ngân hàng<br>
            📞 Hotline: 0123 456 789<br>
            📧 Email: phongctsc@hvnh.edu.vn
        </p>
    </div>

</body>

</html>