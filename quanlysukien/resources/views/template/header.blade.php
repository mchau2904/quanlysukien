<header class="container-fluid shadow-sm py-3">
    <div class="container" style="max-width:1530px">
        <div class="row align-items-center">
            {{-- Logo --}}
            <div class="col-md-1 d-flex align-items-center">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="img-fluid" style="max-height: 40px;">
                </a>
            </div>


            {{-- Navbar --}}
            <div class="col-md-8">
                <nav class="navbar navbar-expand-lg">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}">Trang chủ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('events.index') }}">Sự kiện</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('attendance.form') }}">Điểm danh</a>
                            </li> -->


                            @auth
                            @if(auth()->user()->role === 'student')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('registrations.mine') }}">Sự kiện của tôi</a>
                            </li>
                            @endif
                            @endauth
                            @auth
                            @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('report.index') }}">Báo cáo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('evaluation.index') }}">Đánh giá</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('students.index') }}">Quản lý sinh viên</a>
                            </li>
                             {{-- ✅ Chỉ tài khoản System Admin (user_id = 1) mới thấy --}}
                            @if(auth()->user()->user_id == 1)
                            <li class="nav-item">
                                <a class="nav-link " href="{{ route('admins.index') }}">
                                    Quản lý cán bộ
                                </a>
                            </li>
                            @endif
                            @endif
                            @endauth
                        </ul>
                    </div>
                </nav>
            </div>

            {{-- User actions --}}
            <div class="col-md-3 text-end">
                <!-- <button class="btn btn-thong-bao">Thông Báo</button> -->
                {{-- Thông báo --}}
                @auth
                <div class="dropdown d-inline-block me-3">
                    <button class="btn btn-thong-bao position-relative" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i> Thông Báo
                        @php
                        $userId = auth()->id();

                        // Đếm số thông báo chưa đọc
                        $unreadCount = \DB::table('notifications as n')
                        ->where(function($q) use ($userId) {
                        $q->where('n.user_id', $userId)
                        ->orWhereNull('n.user_id');
                        })
                        ->whereNotIn('n.id', function($sub) use ($userId) {
                        $sub->select('notification_id')
                        ->from('notification_reads')
                        ->where('user_id', $userId);
                        })
                        ->count();
                        @endphp

                        @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow"
                        style="min-width:300px;max-height:400px;overflow:auto;">
                        @php
                        $notifications = \DB::table('notifications')
                        ->where(function($q){
                        $q->where('user_id', auth()->id())->orWhereNull('user_id');
                        })
                        ->orderBy('created_at','desc')
                        ->limit(10)
                        ->get();
                        @endphp

                        @forelse($notifications as $n)
                      @php
                      $link = '/'; // 🔸 link mặc định an toàn

                      if (!empty($n->event_id)) {
                          // 🔹 Mặc định: mở trang sự kiện
                          $link = route('events.show', ['event' => $n->event_id]);

                          // 🔹 Nếu sinh viên nhận phản hồi từ giáo viên
                          if ($n->type === 'teacher_feedback') {
                              $link = route('evaluations.show', ['event_id' => $n->event_id]);
                          }
                          // 🔹 Nếu giáo viên nhận phản hồi từ sinh viên
                          elseif ($n->type === 'feedback') {
                              $link = route('evaluations.index', ['event_id' => $n->event_id]);
                          }
                      }
                      @endphp


                        <li class="px-3 py-2 border-bottom small">
                            <a href="{{ $link }}" class="text-decoration-none d-block">
                                <strong>{{ $n->title }}</strong><br>
                                <span class="text-muted">{{ $n->message }}</span><br>
                                <small class="text-secondary">
                                    {{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}
                                </small>
                            </a>
                        </li>
                        @empty
                        <li class="text-center text-muted py-3">Không có thông báo</li>
                        @endforelse
                    </ul>
                </div>

                @else
                <div class="d-inline-block me-3">
                    <button class="btn btn-thong-bao" onclick="window.location.href='{{ route('login.show') }}'">
                        <i class="bi bi-bell"></i> Thông Báo
                    </button>
                </div>
                @endauth

                @auth
                <div class="dropdown d-inline-block">
                    <button class="btn p-0 border-0 bg-transparent" data-bs-toggle="dropdown">
                        <img src="{{ auth()->user()->avatar_url ?? 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(auth()->user()->email ?? 'no@local'))) . '?s=80&d=identicon' }}"
                            class="rounded-circle shadow-sm" style="width:36px;height:36px;object-fit:cover" alt="avatar">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width:240px;border-radius:12px;">
                        <li class="px-3 py-2">
                            <div class="fw-semibold">{{ auth()->user()->full_name ?? auth()->user()->username }}</div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Thay đổi thông tin</a></li>
                        @auth
                        @if(auth()->user()->role === 'student')
                        <li><a class="dropdown-item" href="{{ route('registrations.mine') }}">Sự kiện của tôi</a></li>
                        @endif
                        @endauth
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">@csrf
                                <button type="submit" class="dropdown-item text-danger">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <a href="{{ route('login.show') }}" class="btn btn-dang-xuat">Đăng nhập</a>
                @endauth

            </div>


        </div>
    </div>
</header>
<script>
    document.addEventListener('DOMContentLoaded', function() {
  const bell = document.querySelector('.btn-thong-bao');
  if (bell) {
    bell.addEventListener('click', async () => {
      try {
        // 🔹 Gửi request Laravel để đánh dấu đã đọc trong DB
        const res = await fetch('{{ route("notifications.markAsRead") }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          }
        });

        if (res.ok) {
          // 🔹 Xóa badge ở giao diện
          const badge = bell.querySelector('.badge');
          if (badge) badge.remove();

          // 🔹 Xóa node thông báo trong Firebase (đã xem)
          import("https://www.gstatic.com/firebasejs/10.7.2/firebase-app.js")
            .then(({ initializeApp, getApps }) => {
              import("https://www.gstatic.com/firebasejs/10.7.2/firebase-database.js")
                .then(({ getDatabase, ref, remove, get }) => {
                  const apps = getApps();
                  const app = apps.length ? apps[0] : initializeApp(window.firebaseConfig);
                  const db = getDatabase(app);

                  const currentUserId = @json(auth()->user()->user_id ?? null);
                  if (currentUserId) {
                    const notifyRef = ref(db, `Notifications/${currentUserId}`);
                    get(notifyRef).then(snapshot => {
                      if (snapshot.exists()) {
                        snapshot.forEach(child => {
                          remove(ref(db, `Notifications/${currentUserId}/${child.key}`));
                        });
                      }
                    });
                  }
                });
            });
        }
      } catch (err) {
        console.error('❌ Lỗi khi đánh dấu thông báo đã đọc:', err);
      }
    });
  }
});

</script>
<script type="module">
import { initializeApp, getApps } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-app.js";
import { getDatabase, ref, onChildAdded } from "https://www.gstatic.com/firebasejs/10.7.2/firebase-database.js";

// 🧩 Chỉ khởi tạo Firebase nếu chưa có
const apps = getApps();
const app = apps.length ? apps[0] : initializeApp(window.firebaseConfig);
const db = getDatabase(app);

// 🧩 Lấy user hiện tại từ Laravel
const currentUserId = @json(auth()->user()->user_id ?? null);
const currentRole = @json(auth()->user()->role ?? null); // 👈 thêm role để xác định người nhận

if (currentUserId) {
  const notifyRef = ref(db, `Notifications/${currentUserId}`);

  console.log("👂 Listening notifications for:", currentUserId);

  onChildAdded(notifyRef, (snap) => {
    const n = snap.val();
    if (!n) return;
    console.log("🔔 New notification:", n);

    const bellBtn = document.querySelector('.btn-thong-bao');
    const dropdownMenu = bellBtn?.nextElementSibling;
    if (!dropdownMenu) return;

    // 🟢 Badge tăng số
    let badge = bellBtn.querySelector('.badge');
    if (!badge) {
      badge = document.createElement('span');
      badge.className = "position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger";
      badge.textContent = "1";
      bellBtn.appendChild(badge);
    } else {
      badge.textContent = parseInt(badge.textContent || "0") + 1;
    }

    // 🧭 Xác định link đích theo role
    let link = "#";
    if (n.event_id) {
      if (currentRole === "student") {
        link = `{{ route('evaluations.show', ':event_id') }}`.replace(':event_id', n.event_id);
      } else if (currentRole === "admin" || currentRole === "teacher") {
        link = `{{ route('evaluations.index', ':event_id') }}`.replace(':event_id', n.event_id);
      }
    }

    // 🧩 Thêm thông báo mới vào danh sách (prepend, không ghi đè)
    const li = document.createElement('li');
    li.className = "px-3 py-2 border-bottom small fade-in";
    li.innerHTML = `
      <a href="${link}" class="text-decoration-none d-block">
        <strong>${n.title || 'Thông báo mới'}</strong><br>
        <span class="text-muted">${n.message || ''}</span><br>
        <small class="text-secondary">${new Date(n.created_at).toLocaleTimeString()}</small>
      </a>
    `;
    dropdownMenu.prepend(li);

    // 💬 Hiện toast nhỏ góc phải
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-bg-primary border-0 show';
    toast.style.position = 'fixed';
    toast.style.bottom = '20px';
    toast.style.right = '20px';
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">
          🔔 ${n.title || 'Thông báo'}:<br>${n.message || ''}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
  });
}
</script>


<style>
.fade-in {
  animation: fadeIn 0.4s ease-in;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-6px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
