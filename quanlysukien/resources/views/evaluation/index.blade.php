@extends('layouts.app')
@section('title', 'Đánh giá sự kiện')

@section('content')
    <div class="container mt-4">
        <div class="mb-3">
            <a href="{{ route('evaluation.index') }}" class="btn btn-outline-secondary">
                ⬅ Quay lại danh sách sự kiện
            </a>
        </div>

        <h4>Đánh giá sự kiện: {{ $event->event_name }}</h4>

        {{-- Form tìm kiếm + bộ lọc --}}
        <form method="GET" class="row g-2 mb-3 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc mã SV"
                    value="{{ $search }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả sinh viên --</option>
                    <option value="checked" {{ request('status') == 'checked' ? 'selected' : '' }}>✅ Đã điểm danh</option>
                    <option value="unchecked" {{ request('status') == 'unchecked' ? 'selected' : '' }}>⚠️ Chưa điểm danh
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100">Lọc</button>
            </div>
        </form>

        {{-- Bộ chọn đánh giá hàng loạt --}}
        <div class="d-flex align-items-center mb-3 gap-2">
            <div class="form-check me-3">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label" for="selectAll">Chọn tất cả</label>
            </div>

            <select id="bulkScore" class="form-select w-auto">
                <option value="">-- Chọn mức đánh giá --</option>
                <option value="0">0</option>
                <option value="1">1</option>
            </select>

            <button type="button" class="btn btn-success" id="applyBulkBtn">Áp dụng</button>
        </div>

        {{-- Form lưu điểm đánh giá --}}
        <form action="{{ route('evaluations.store') }}" method="POST">
            @csrf
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="selectAllHeader"></th>
                        <th>STT</th>
                        <th>Mã SV</th>
                        <th>Họ tên</th>
                        <th>Ảnh điểm danh</th>
                        <th>Bắt đầu</th>
                        <th>Kết thúc</th>
                        <th>Điểm danh</th>
                        <th>Điểm</th>
                        <th>Phản hồi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $s)
                        <tr>
                            <td><input type="checkbox" class="row-checkbox" data-index="{{ $loop->index }}"></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->username }}</td>
                            <td>{{ $s->full_name }}</td>
                            <td>
                                @if (!empty($s->image_url))
                                    <img src="{{ $s->image_url }}" class="rounded"
                                        style="width:70px;height:70px;object-fit:cover;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $s->start_time }}</td>
                            <td>{{ $s->end_time }}</td>
                            <td>
                                @if (!empty($s->checkin_time))
                                    <span class="text-success fw-semibold">{{ $s->checkin_time }}</span>
                                @else
                                    <span class="badge bg-secondary">Chưa điểm danh</span>
                                @endif
                            </td>
                            <td>
                                <select name="evaluations[{{ $loop->index }}][score]" class="form-select">
                                    <option value="0" {{ $s->score == 0 ? 'selected' : '' }}>0</option>
                                    <option value="1" {{ $s->score == 1 ? 'selected' : '' }}>1</option>
                                </select>
                                <input type="hidden" name="evaluations[{{ $loop->index }}][event_id]"
                                    value="{{ $event->event_id }}">
                                <input type="hidden" name="evaluations[{{ $loop->index }}][user_id]"
                                    value="{{ $s->user_id }}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="openFeedbackModal({{ $event->event_id }}, {{ $s->user_id }}, '{{ $s->full_name }}')">
                                    💬 Xem
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-muted text-center py-3">Không có sinh viên nào phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary mt-3">💾 Lưu lại</button>
        </form>
    </div>

    {{-- === Modal chat realtime === --}}
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Phản hồi với <span id="studentName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="feedbackContent" class="p-2" style="max-height:400px; overflow-y:auto;">
                        <p class="text-muted">Đang tải...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="input-group">
                        <input type="text" id="replyInput" class="form-control" placeholder="Nhập phản hồi...">
                        <button class="btn btn-primary" id="sendReplyBtn">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- === Script: chọn tất cả + áp dụng điểm === --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const selectAllHeader = document.getElementById('selectAllHeader');
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.row-checkbox');

            [selectAll, selectAllHeader].forEach(control => {
                control?.addEventListener('change', e => {
                    checkboxes.forEach(cb => cb.checked = e.target.checked);
                    if (selectAll) selectAll.checked = e.target.checked;
                    if (selectAllHeader) selectAllHeader.checked = e.target.checked;
                });
            });

            const applyBtn = document.getElementById('applyBulkBtn');
            const bulkScore = document.getElementById('bulkScore');

            applyBtn.addEventListener('click', () => {
                const score = bulkScore.value;
                if (!score && score !== "0") {
                    alert('⚠️ Vui lòng chọn mức đánh giá.');
                    return;
                }

                const checked = document.querySelectorAll('.row-checkbox:checked');
                if (checked.length === 0) {
                    alert('⚠️ Vui lòng chọn ít nhất một sinh viên.');
                    return;
                }

                checked.forEach(cb => {
                    const row = cb.closest('tr');
                    const select = row.querySelector('select[name^="evaluations"]');
                    if (select) select.value = score;
                });

                const toast = document.createElement('div');
                toast.className = 'toast align-items-center text-bg-primary border-0 show';
                toast.style.position = 'fixed';
                toast.style.bottom = '20px';
                toast.style.right = '20px';
                toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">✅ Đã áp dụng mức đánh giá ${score} cho ${checked.length} sinh viên.</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 4000);
            });
        });
    </script>
    {{-- Đưa PHP ra ngoài script --}}
    @php
        $teacherId = auth()->user()->user_id ?? null;
        $teacherName = auth()->user()->full_name ?? 'Giáo viên';
        $eventName = $event->event_name ?? 'Sự kiện';

    @endphp

    <script>
        // Gán sang biến JS thông thường (chưa dùng import ở đây)
        window.currentTeacherId = @json($teacherId);
        window.currentTeacherName = @json($teacherName);
        window.currentEventName = @json($event->event_name);
    </script>

    {{-- Bắt đầu script module tách riêng --}}
    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-app.js";
        import {
            getDatabase,
            ref,
            onValue,
            push,
            off
        } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-database.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCIL-3LokWAsvJvNddUGTXhsBiziysS_8A",
            authDomain: "event-feedback-system.firebaseapp.com",
            databaseURL: "https://event-feedback-system-default-rtdb.firebaseio.com", // ✅ dùng đúng region
            projectId: "event-feedback-system",
            storageBucket: "event-feedback-system.firebasestorage.app",
            messagingSenderId: "347348361462",
            appId: "1:347348361462:web:19d1b8b63bb1467f570cd7",
            measurementId: "G-4Z1YR66324"
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        // 🧩 lấy biến Laravel export ra
        const currentTeacherId = window.currentTeacherId;
        const currentTeacherName = window.currentTeacherName;

        let currentEventId = null;
        let currentStudentId = null;
        let currentChatRef = null; // 🔹 lưu ref đang lắng nghe
        const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));

        // === mở modal phản hồi
        window.openFeedbackModal = (eventId, studentId, fullName) => {
            currentEventId = eventId;
            currentStudentId = studentId;
            document.getElementById('studentName').innerText = fullName;

            const chatBox = document.getElementById('feedbackContent');
            chatBox.innerHTML = '<p class="text-muted">Đang tải phản hồi...</p>';
            modal.show();

            // 🔹 Nếu đang mở chat cũ → ngắt listener
            if (currentChatRef) off(currentChatRef);

            // 🔹 Tạo ref mới cho học sinh hiện tại
            currentChatRef = ref(db, `Feedbacks/${eventId}/${studentId}/messages`);

            // 🔹 Lắng nghe dữ liệu realtime
            onValue(currentChatRef, (snapshot) => {
                const messages = snapshot.val();
                let html = '';
                if (messages) {
                    Object.values(messages).forEach(m => {
                        const isTeacher = m.sender_id == currentTeacherId;
                        html += `
          <div class="p-2 mb-2 rounded ${isTeacher ? 'bg-primary text-white text-end' : 'bg-light'}">
            <strong>${m.sender_name}:</strong> ${m.content}
            <div class="text-muted small">${new Date(m.created_at).toLocaleTimeString()}</div>
          </div>`;
                    });
                } else {
                    html = '<p class="text-muted">Chưa có phản hồi nào.</p>';
                }
                chatBox.innerHTML = html;
                chatBox.scrollTop = chatBox.scrollHeight;
            });
        };

        // === gửi tin nhắn ===
        // === Gửi tin nhắn ===
        document.getElementById('sendReplyBtn').addEventListener('click', async () => {
            const input = document.getElementById('replyInput');
            const content = input.value.trim();
            if (!content) return alert('⚠️ Nhập nội dung.');
            if (!currentEventId || !currentStudentId) return alert('⚠️ Thiếu event_id hoặc user_id.');

            // 1️⃣ Gửi tin nhắn chat realtime
            const chatRef = ref(db, `Feedbacks/${currentEventId}/${currentStudentId}/messages`);
            await push(chatRef, {
                sender_id: currentTeacherId,
                sender_name: currentTeacherName,
                content,
                created_at: new Date().toISOString()
            });

            input.value = '';

            // 2️⃣ Gửi thông báo đến học sinh
            const notifyRef = ref(db, `Notifications/${currentStudentId}`);
            await push(notifyRef, {
                type: "teacher_feedback",
                title: "Phản hồi từ giáo viên",
                message: `${currentTeacherName} vừa phản hồi bạn trong sự kiện "${window.currentEventName}"`,
                event_id: currentEventId,
                sender_id: currentTeacherId,
                created_at: new Date().toISOString(),
            });


            // 3️⃣ Hiện thông báo nhỏ (cho giáo viên xác nhận gửi thành công)
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-bg-success border-0 show';
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.innerHTML = `
      <div class="d-flex">
          <div class="toast-body">✅ Đã gửi phản hồi cho sinh viên!</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        });
    </script>
    <style>
        body {
            background: #f8fafc;
        }

        /* 🌈 Header */
        h4 {
            font-weight: 700;
            color: #334155;
            margin-bottom: 1.5rem;
            border-left: 6px solid #6366f1;
            padding-left: 12px;
        }

        .table>thead {
            vertical-align: middle;
        }

        .table>thead tr th {
            vertical-align: middle;
            text-align: center
        }

        /* 🧭 Form bộ lọc */
        form.row.g-2,
        form.row.g-3 {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            padding: 16px 22px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            font-size: 0.95rem;
            transition: 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.15rem rgba(99, 102, 241, 0.25);
        }

        /* ⚙️ Bulk actions */
        .d-flex.gap-2 {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 14px 18px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        }

        #bulkScore {
            border-radius: 8px;
            min-width: 160px;
        }

        .btn-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border: none;
            border-radius: 50px;
            padding: 6px 20px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
            transition: 0.25s;
        }

        .btn-success:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            border: none;
            border-radius: 50px;
            padding: 6px 24px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
            transition: 0.25s;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* 🧾 Bảng dữ liệu */
        .table {
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.04);
        }

        thead th {
            background: #f9fafb;
            color: #334155;
            font-weight: 600;
            font-size: 0.95rem;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .table th,
        .table td {
            vertical-align: middle !important;
        }

        /* 🗨️ Modal phản hồi */
        .modal-content {
            border-radius: 16px;
            overflow: hidden;
            border: none;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: #fff;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-body {
            background-color: #f9fafc;
            padding: 16px;
        }

        #feedbackContent {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px;
            background: #fff;
            min-height: 300px;
        }

        #feedbackContent .bg-primary {
            border-radius: 10px;
            padding: 10px;
            background: linear-gradient(135deg, #6366f1, #06b6d4) !important;
        }

        #feedbackContent .bg-light {
            border-radius: 10px;
            padding: 10px;
            background: #f1f5f9 !important;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 0 0 16px 16px;
        }

        #replyInput {
            border-radius: 8px 0 0 8px;
            border: 1px solid #e2e8f0;
        }

        #sendReplyBtn {
            border-radius: 0 8px 8px 0;
        }

        /* 💬 Toast */
        .toast {
            animation: fadeInUp 0.4s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* 🔹 Ảnh điểm danh */
        img.rounded {
            border: 2px solid #e2e8f0;
            transition: 0.25s;
        }

        img.rounded:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>

@endsection
