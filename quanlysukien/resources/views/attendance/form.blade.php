@extends('layouts.app')

@section('title', 'Điểm danh')

@section('content')

<style>
    /* === CUSTOM UI STYLE === */
    .attendance-card {
        background: #fff;
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .attendance-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    }

    .btn-gradient {
        background: linear-gradient(90deg, #3b82f6, #6366f1);
        width: 100%;
        max-width: 200px;
        color: #fff;
        border: none;
        border-radius: 20px !important;
        transition: all 0.2s;
        margin-bottom: 8px
    }

    .button {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .button a {
        text-decoration: none;
        font-size: 12px;
        color: #92999e;
    }

    .btn-custom {
        border-radius: 30px;
        padding: 6px 12px;
        border: none;
        cursor: pointer;
        transition: 0.2s;
        width: 100%;
        max-width: 280px;
    }

    .image-camera {
        background-color: transparent;
        display: block;
        width: 100%;
        height: auto;
        object-fit: contain;
        border-radius: 20px
    }

    .btn-gradient:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .camera-frame {
        border-radius: 16px;
        overflow: hidden;
        background: #000;
        position: relative;
    }

    .camera-frame video,
    .camera-frame img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .camera-overlay {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(255, 255, 255, 0.7);
        color: #111;
        font-size: 0.85rem;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .file-label {
        font-weight: 600;
        color: #444;
        font-size: 12px;
        margin-bottom: 6px;
    }

    .btn-group-custom .btn {
        border-radius: 30px;
        font-weight: 500;
        transition: 0.2s;
    }

    .btn-group-custom .btn:hover {
        transform: translateY(-1px);
    }

    #result img {
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .text-muteds {
        font-size: 13px;
        margin-bottom: 0px;
    }

    .upload-file {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center
    }

    .upload-file input[type="file"] {
        border-radius: 12px;
        width: 100%;
        cursor: pointer;
        max-width: 350px;
    }
</style>

<div class="container py-5">
    <div class="attendance-card mx-auto" style="max-width: 550px;">

        {{-- ✅ Thông tin sự kiện --}}
        @isset($event)
        <div class="mb-3 p-3 rounded-3" style="background:#f8fafc;border:1px solid #eef2f7">
            <div class="d-flex align-items-start gap-3">
                <div class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">Sự kiện</div>
                <div>
                    <div class="fw-bold">{{ $event->event_name }}</div>
                    <div class="text-muted small">
                        {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('d/m/Y H:i') }}
                        –
                        {{ \Illuminate\Support\Carbon::parse($event->end_time)->format('d/m/Y H:i') }}
                        @if($event->location) • {{ $event->location }} @endif
                    </div>
                </div>
            </div>
        </div>
        @endisset


        {{-- ========================================= --}}
        {{-- ✅ NẾU ĐÃ ĐIỂM DANH --}}
        {{-- ========================================= --}}
        @if(!empty($alreadyChecked) && $alreadyChecked)
        <div class="text-center py-4">
            <h4 class="fw-bold text-success mb-3">✅ Bạn đã điểm danh sự kiện này</h4>
            <p class="text-muted text-muteds mb-3">Hệ thống ghi nhận bạn đã có mặt. Cảm ơn bạn!</p>

            @if(!empty($attendance->image_url))
            <img src="{{ $attendance->image_url }}" alt="Ảnh điểm danh" class="img-fluid rounded shadow-sm mb-3" style="max-height:250px;">
            @endif

            <a href="{{ route('registrations.mine') }}" class="btn btn-outline-secondary w-100 py-2">
                ← Quay lại trang đăng ký
            </a>
        </div>


        {{-- ========================================= --}}
        {{-- 🚀 NẾU CHƯA ĐIỂM DANH --}}
        {{-- ========================================= --}}
        @else
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-2 text-primary header">Điểm danh bằng hình ảnh</h3>
            <p class="text-muted text-muteds">Hệ thống xác nhận danh tính qua ảnh hoặc camera thiết bị của bạn.</p>
        </div>

        <form id="attendanceForm" enctype="multipart/form-data">
            @csrf

            {{-- ✅ Gửi event_id lên server --}}
            <input type="hidden" id="event_id" name="event_id" value="{{ $event->event_id ?? request('event_id') }}" />

            <!-- KHU VỰC CAMERA -->
            <div class="camera-frame mb-3">
                <video id="camera" autoplay playsinline style="display:none;"></video>
                <canvas id="snapshot" style="display:none;"></canvas>
                <img id="preview" src="" alt="Preview" class="img-fluid" style="display:none;">
                <span class="camera-overlay" id="cameraStatus">Camera chưa bật</span>
            </div>

            <!-- NÚT CAMERA -->
            <div class="d-flex justify-content-center mb-4 gap-2 flex-wrap btn-group-custom">
                <button type="button" id="startCamera" class="btn btn-custom">
                    <img src="{{ asset('img/camera3.jpg') }}" alt="Camera" class="image-camera">
                </button>
                <button type="button" id="captureBtn" class="btn btn-outline-success btn-sm px-3" style="display:none;">📸 Chụp ảnh</button>
                <button type="button" id="retakeBtn" class="btn btn-outline-secondary btn-sm px-3" style="display:none;">🔁 Chụp lại</button>
            </div>

            <!-- UPLOAD FILE -->
            <div class="mb-4 upload-file">
                <label for="photo" class="file-label">Tải lên từ thư viện</label>
                <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
            </div>

            <!-- NÚT SUBMIT -->
            <div class="button">
                <button type="submit" class="btn btn-gradient w-100 py-2">Gửi điểm danh</button>
                <a href="{{ route('registrations.mine') }}">
                    Quay lại trang đăng ký
                </a>
            </div>

        </form>

        <div id="result" class="mt-4 text-center"></div>
        @endif
    </div>
</div>


<script>
    const startBtn = document.getElementById('startCamera');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const video = document.getElementById('camera');
    const canvas = document.getElementById('snapshot');
    const preview = document.getElementById('preview');
    const cameraStatus = document.getElementById('cameraStatus');
    let stream = null;

    // === 🎥 MỞ CAMERA ===
    startBtn.addEventListener('click', async () => {
        try {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Trình duyệt của bạn không hỗ trợ mở camera.');
                return;
            }

            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user'
                }
            });
            video.srcObject = stream;
            video.style.display = 'block';
            captureBtn.style.display = 'inline-block';
            startBtn.style.display = 'none';
            cameraStatus.textContent = 'Camera đang bật';
        } catch (err) {
            alert('Không thể mở camera: ' + err.message);
        }
    });

    // === 📸 CHỤP ẢNH ===
    captureBtn.addEventListener('click', () => {
        if (!stream) return alert('Camera chưa mở');
        const ctx = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0);

        // Ẩn video, hiển thị ảnh chụp
        video.style.display = 'none';
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'inline-block';
        preview.src = canvas.toDataURL('image/png');
        preview.style.display = 'block';
        cameraStatus.textContent = 'Đã chụp ảnh';

        document.getElementById('photo').value = "";

        // Tắt camera
        stream.getTracks().forEach(track => track.stop());
    });

    // === 🔁 CHỤP LẠI ===
    retakeBtn.addEventListener('click', () => {
        preview.style.display = 'none';
        retakeBtn.style.display = 'none';
        startBtn.style.display = 'inline-block';
        cameraStatus.textContent = 'Camera chưa bật';

        document.getElementById('photo').value = "";
    });


    // ⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐
    // ⭐ KHI UPLOAD ẢNH → CLEAR TOÀN BỘ CAMERA
    // ⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐⭐
    photo.addEventListener('change', () => {

        // 1️⃣ Tắt camera nếu đang mở
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        video.srcObject = null;
        video.style.display = 'none';

        // 2️⃣ Xóa ảnh chụp trên canvas và preview
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        preview.src = "";
        preview.style.display = 'none';

        // 3️⃣ Reset lại trạng thái các nút camera
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'none';
        startBtn.style.display = 'inline-block';

        // 4️⃣ Cập nhật trạng thái
        cameraStatus.textContent = 'Đang dùng ảnh tải lên';
    });

    // === 📤 GỬI FORM ===
    document.getElementById('attendanceForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const resultDiv = document.getElementById('result');
        resultDiv.innerHTML = '';

        const formData = new FormData();

        // ✅ GỬI KÈM event_id
        const eventId = document.getElementById('event_id').value;
        if (!eventId) {
            alert('Thiếu mã sự kiện.');
            return;
        }
        formData.append('event_id', eventId);

        // Ảnh từ camera hoặc file
        if (preview.src && preview.style.display === 'block') {
            const blob = await (await fetch(preview.src)).blob();
            formData.append('photo', blob, 'camera_capture.png');
        } else {
            const file = document.getElementById('photo').files[0];
            if (!file) {
                alert('Vui lòng chụp hoặc chọn ảnh!');
                return;
            }
            formData.append('photo', file);
        }

        try {
            const res = await fetch("{{ route('attendance.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    // ✅ Báo cho Laravel trả JSON (validation/redirect cũng thành JSON)
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                credentials: "same-origin"
            });

            const data = await res.json().catch(() => null);
            if (!data) {
                const textBody = await res.text();
                resultDiv.innerHTML = `<div class='alert alert-danger'>⚠️ Server trả về dữ liệu không hợp lệ:<pre>${textBody}</pre></div>`;
                return;
            }

            if (!res.ok) {
                // 422 validation sẽ có errors
                if (data.errors) {
                    const firstErr = Object.values(data.errors)[0]?.[0] || 'Lỗi dữ liệu.';
                    resultDiv.innerHTML = `<div class='alert alert-danger'>${firstErr}</div>`;
                } else {
                    resultDiv.innerHTML = `<div class='alert alert-danger'>${data.error || 'Lỗi không xác định'} (${res.status})</div>`;
                }
                return;
            }

            // ✅ Thành công
            resultDiv.innerHTML = `
        <div class='alert alert-success fw-semibold'>✅ ${data.message}</div>
        ${data.image_url ? `<img src="${data.image_url}" alt="Ảnh điểm danh" class="img-fluid mt-3" style="max-height:200px;">` : ''}
        <p class="text-muted mt-2 small">📍 IP: ${data.ip || 'Không xác định'}</p>
      `;
        } catch (err) {
            resultDiv.innerHTML = `<div class='alert alert-danger'>💥 Lỗi mạng hoặc server!<br><pre>${err.message}</pre></div>`;
        }
    });
</script>


@endsection