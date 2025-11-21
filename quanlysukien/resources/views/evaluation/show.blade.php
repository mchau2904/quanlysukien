@extends('layouts.app')
@section('title', 'Đánh giá sự kiện')
@section('content')

<div class="container py-4" style="max-width: 800px;">
    {{-- === Header === --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            ⭐ Đánh giá sự kiện: {{ $event->event_name }}
        </h4>
        <a href="{{ route('registrations.mine') }}" class="btn btn-outline-secondary rounded-pill px-3">
            ← Quay lại
        </a>
    </div>

    {{-- === Thông tin sự kiện === --}}
    <div class="mb-4 p-3 border rounded bg-light">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <div><strong>Địa điểm:</strong> {{ $event->location ?? '—' }}</div>
                <div>
                    <strong>Thời gian:</strong>
                    {{ \Carbon\Carbon::parse($event->start_time)->format('d/m/Y H:i') }} –
                    {{ \Carbon\Carbon::parse($event->end_time)->format('d/m/Y H:i') }}
                </div>
            </div>
          <div class="text-md-end mt-3 mt-md-0">
    <h5 class="text-success mb-0">
    Mức đánh giá:
    <span class="fw-bold">
        @if(is_null($score))
            Chờ cập nhật đánh giá
        @elseif($score == 1)
            Ghi nhận
        @elseif($score == 0)
            Không ghi nhận
        @endif
    </span>
</h5>

</div>

        </div>
    </div>

    {{-- === Khu vực chat realtime với giáo viên === --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
            💬 Phản hồi với giáo viên
        </div>
        <div class="card-body">
            <div id="chatBox" class="p-2 border rounded bg-light" style="max-height: 400px; overflow-y: auto;">
                <p class="text-muted">Đang tải phản hồi...</p>
            </div>

            <div class="input-group mt-3">
                <input type="text" id="replyInput" class="form-control" placeholder="Nhập phản hồi của bạn...">
                <button class="btn btn-primary" id="sendReplyBtn">Gửi</button>
            </div>
        </div>
    </div>
</div>

{{-- === PHP export biến hiện tại === --}}
@php
    $studentId = auth()->user()->user_id;
    $studentName = auth()->user()->full_name;
    $teacherId = $event->manager_id ?? 1; // id giáo viên quản lý sự kiện
    $eventName = $event->event_name ?? 'Sự kiện';
@endphp

<script>
    window.currentStudentId = @json($studentId);
    window.currentStudentName = @json($studentName);
    window.currentTeacherId = @json($teacherId);
    window.currentEventId = @json($event->event_id);
    window.currentEventName = @json($event->event_name); // ✅ thêm dòng này
</script>

{{-- === Firebase realtime script === --}}
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-app.js";
import { getDatabase, ref, onValue, push, off } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-database.js";

const firebaseConfig = {
  apiKey: "AIzaSyCIL-3LokWAsvJvNddUGTXhsBiziysS_8A",
  authDomain: "event-feedback-system.firebaseapp.com",
  databaseURL: "https://event-feedback-system-default-rtdb.firebaseio.com",
  projectId: "event-feedback-system",
  storageBucket: "event-feedback-system.firebasestorage.app",
  messagingSenderId: "347348361462",
  appId: "1:347348361462:web:19d1b8b63bb1467f570cd7",
  measurementId: "G-4Z1YR66324"
};

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

// === Biến từ Laravel export ===
const eventId = window.currentEventId;
const studentId = window.currentStudentId;
const teacherId = window.currentTeacherId;
const studentName = window.currentStudentName;

// === Khởi tạo tham chiếu Firebase
const chatRef = ref(db, `Feedbacks/${eventId}/${studentId}/messages`);
const chatBox = document.getElementById('chatBox');
const input = document.getElementById('replyInput');
const sendBtn = document.getElementById('sendReplyBtn');

// === Lắng nghe realtime phản hồi
onValue(chatRef, (snapshot) => {
    const messages = snapshot.val();
    let html = '';
    if (messages) {
        Object.values(messages).forEach(m => {
            const isStudent = m.sender_id == studentId;
            html += `
                <div class="p-2 mb-2 rounded ${isStudent ? 'bg-primary text-white text-end' : 'bg-light'}">
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


sendBtn.addEventListener('click', async () => {
  const content = input.value.trim();
  if (!content) return alert('⚠️ Vui lòng nhập nội dung.');

  const eventName = window.currentEventName; // ✅ lấy tên sự kiện
  const eventId = window.currentEventId;

  // 1️⃣ Gửi tin nhắn vào Firebase (chat realtime)
  await push(chatRef, {
      sender_id: studentId,
      sender_name: studentName,
      content,
      created_at: new Date().toISOString()
  });

  input.value = '';

  // 2️⃣ Gửi thông báo đến giáo viên
  const notifyRef = ref(db, `Notifications/${teacherId}`);
  await push(notifyRef, {
      type: "student_feedback",
      title: "Phản hồi mới từ sinh viên",
      message: `${studentName} vừa phản hồi trong sự kiện "${eventName}"`, // ✅ thay ở đây
      event_id: eventId,
      sender_id: studentId,
      created_at: new Date().toISOString()
  });

  // 3️⃣ Hiển thị toast xác nhận
  const toast = document.createElement('div');
  toast.className = 'toast align-items-center text-bg-success border-0 show';
  toast.style.position = 'fixed';
  toast.style.bottom = '20px';
  toast.style.right = '20px';
  toast.innerHTML = `
      <div class="d-flex">
          <div class="toast-body">✅ Phản hồi đã được gửi!</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>`;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 5000);
});

</script>

<style>
    .bg-light { background: #f8f9fa !important; }
    .text-end strong { color: #fff; }
</style>

@endsection
