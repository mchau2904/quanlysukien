{{-- resources/views/events/show.blade.php --}}
@extends('layouts.app')
@section('title', $event->event_name)

@section('content')

@php
use Illuminate\Support\Carbon;

// Bảo đảm luôn là Carbon (phòng khi chưa cast trong Model)
$start = $event->start_time instanceof \Carbon\Carbon ? $event->start_time : Carbon::parse($event->start_time);
$end = $event->end_time instanceof \Carbon\Carbon ? $event->end_time : Carbon::parse($event->end_time);
$created = $event->created_at ? ( $event->created_at instanceof \Carbon\Carbon ? $event->created_at : Carbon::parse($event->created_at) ) : null;

$now = now();
$status = $end->lt($now) ? 'past' : ($start->gt($now) ? 'upcoming' : 'ongoing');
$registeredCount = DB::table('event_registration')
    ->where('event_id', $event->event_id)
    ->count();
@endphp
<div class="container py-4" style="max-width:980px">

    {{-- Breadcrumb + tiêu đề --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Sự kiện</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $event->event_name }}</li>
            </ol>
        </nav>

        <div>
            @if($status==='ongoing')
            <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">Đang diễn ra</span>
            @elseif($status==='upcoming')
            <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">Sắp diễn ra</span>
            @else
            <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">Đã kết thúc</span>
            @endif
        </div>
    </div>

    {{-- Hero/Card --}}
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="ratio ratio-21x9 bg-light">
        <img
    src="{{ $event->image_url ?: 'https://picsum.photos/seed/ongoing' . $event->event_id . '/400/220' }}"
    class="card-img-top"
    alt="Ảnh sự kiện {{ $event->event_name }}"
    style="object-fit: cover; height: 220px;">

        </div>

        <div class="card-body p-4">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <h3 class="fw-bold mb-0">{{ $event->event_name }}</h3>

                {{-- Actions cho admin --}}
                @auth
                @if(auth()->user()->role === 'admin')
                <div class="d-flex gap-2">
                    <a href="{{ route('events.edit',$event->event_id) }}" class="btn btn-sm btn-outline-warning">
                        <i class="bi bi-pencil-square me-1"></i> Sửa
                    </a>
                    <form action="{{ route('events.destroy',$event->event_id) }}" method="POST"
                        onsubmit="return confirm('Xoá sự kiện này?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash me-1"></i> Xoá
                        </button>
                    </form>
                </div>
                @endif
                @endauth
            </div>

            {{-- Chips thông tin ngắn --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                @if($event->event_code)
                <span class="chip"><i class="bi bi-upc-scan me-1"></i> Mã: {{ $event->event_code }}</span>
                @endif
                @if($event->organizer)
                <span class="chip"><i class="bi bi-building me-1"></i> {{ $event->organizer }}</span>
                @endif
                @if($event->level)
                <span class="chip"><i class="bi bi-diagram-3 me-1"></i> {{ $event->level }}</span>
                @endif
                @if($event->semester)
                <span class="chip"><i class="bi bi-journal-bookmark me-1"></i> HK: {{ $event->semester }}</span>
                @endif
                @if($event->academic_year)
                <span class="chip"><i class="bi bi-calendar2-week me-1"></i> {{ $event->academic_year }}</span>
                @endif
                @if(!is_null($event->max_participants))
                <span class="chip"><i class="bi bi-people me-1"></i> Tối đa: {{ $event->max_participants }}</span>
                @endif
              @auth
                @if(auth()->user()->role === 'admin')
                    @if(!is_null($event->max_participants))
                        <span class="chip"
                            role="button"
                            data-event-id="{{ $event->event_id }}"
                            id="view-registered"
                            title="Xem danh sách sinh viên đã đăng ký">
                            <i class="bi bi-people me-1"></i>
                            Đã đăng ký: {{ $registeredCount }} / {{ $event->max_participants }}
                        </span>
                    @else
                        <span class="chip">
                            <i class="bi bi-people me-1"></i>
                            Đã đăng ký: {{ $registeredCount }}
                        </span>
                    @endif
                @endif
            @endauth



                @if($created)
                <span class="chip"><i class="bi bi-clock-history me-1"></i> Tạo: {{ $created->format('d/m/Y H:i') }}</span>
                @endif
            </div>

            {{-- Grid thông tin chi tiết --}}
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="info">
                        <div class="info-label"><i class="bi bi-hourglass-split me-1"></i>Thời gian</div>
                        <div class="info-value">
                            {{ $start->format('d/m/Y H:i') }} – {{ $end->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info">
                        <div class="info-label"><i class="bi bi-calendar-check me-1"></i>Hạn đăng ký</div>
                        <div class="info-value">
                            {{ $event->registration_deadline
                ? \Illuminate\Support\Carbon::parse($event->registration_deadline)->format('d/m/Y H:i')
                : 'Không giới hạn' }}
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="info">
                        <div class="info-label"><i class="bi bi-geo-alt me-1"></i>Địa điểm</div>
                        <div class="info-value">{{ $event->location ?: 'Chưa cập nhật' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info">
                        <div class="info-label"><i class="bi bi-person-badge me-1"></i>Cán bộ quản lý</div>
                        <div class="info-value">{{ $event->manager?->full_name ?? '—' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info">
                        <div class="info-label"><i class="bi bi-flag me-1"></i>Trạng thái</div>
                        <div class="info-value">
                            @if($status==='ongoing')
                            <span class="badge bg-success-subtle text-success">Đang diễn ra</span>
                            @elseif($status==='upcoming')
                            <span class="badge bg-primary-subtle text-primary">Sắp diễn ra</span>
                            @else
                            <span class="badge bg-secondary-subtle text-secondary">Đã kết thúc</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mô tả --}}
            <div class="mb-3">
                <div class="info-label mb-1"><i class="bi bi-card-text me-1"></i>Mô tả</div>
                <div class="lh-base">{!! nl2br(e($event->description ?? 'Chưa có mô tả.')) !!}</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('events.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại
                </a>

                @auth
                @if(auth()->user()->role === 'student' && $status !== 'past')
                <form action="{{ route('registrations.store', $event->event_id) }}" method="POST"
                    onsubmit="return confirmRegister('{{ $event->event_name }}', '{{ $start->format('d/m/Y H:i') }}', '{{ $end->format('d/m/Y H:i') }}')">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-pill">
                        <i class="bi bi-check-circle me-1"></i> Đăng ký tham gia
                    </button>
                </form>
                @endif
                @endauth
            </div>
        </div>
    </div>
</div>
<!-- Modal danh sách sinh viên -->
<div class="modal fade" id="registeredModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Danh sách sinh viên đã đăng ký</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="registeredList" class="table-responsive">
          <p class="text-muted text-center">Đang tải dữ liệu...</p>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewBtn = document.getElementById('view-registered');
    if (!viewBtn) return;

    // Khi admin bấm "Đã đăng ký"
    viewBtn.addEventListener('click', async () => {
        const eventId = viewBtn.getAttribute('data-event-id');
        const modal = new bootstrap.Modal(document.getElementById('registeredModal'));
        const listDiv = document.getElementById('registeredList');
        listDiv.innerHTML = `<p class="text-muted text-center">⏳ Đang tải danh sách...</p>`;
        modal.show();
        await loadList(eventId);
    });

    // Hàm tải danh sách sinh viên + lọc
    async function loadList(eventId, cls = '', fac = '') {
        const listDiv = document.getElementById('registeredList');
        try {
            const baseUrl = "{{ url('/events') }}";
            const fetchUrl = `${baseUrl}/${eventId}/registrations?class=${encodeURIComponent(cls)}&faculty=${encodeURIComponent(fac)}`;
            console.log("📡 Fetch:", fetchUrl);

            const res = await fetch(fetchUrl);
            const data = await res.json();
            if (data.error) throw new Error(data.error);

            const { students, classes, faculties } = data;

            // 🧩 HTML lọc lớp/khoa
            let filterHtml = `
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <select id="filterClass" class="form-select form-select-sm" style="max-width:180px;">
                        <option value="">-- Lọc theo lớp --</option>
                        ${classes.map(c => c ? `<option value="${c}" ${c === cls ? 'selected' : ''}>${c}</option>` : '').join('')}
                    </select>
                    <select id="filterFaculty" class="form-select form-select-sm" style="max-width:180px;">
                        <option value="">-- Lọc theo khoa --</option>
                        ${faculties.map(f => f ? `<option value="${f}" ${f === fac ? 'selected' : ''}>${f}</option>` : '').join('')}
                    </select>
                </div>
            `;

            // 🧩 Nếu chưa có sinh viên
            if (!students.length) {
                listDiv.innerHTML = filterHtml + `<p class="text-center text-muted">Chưa có sinh viên nào đăng ký.</p>`;
                attachFilters(eventId); // vẫn cần gắn filter để cho phép đổi lớp/khoa
                return;
            }

            // 🧩 Bảng dữ liệu sinh viên
            let html = `
                ${filterHtml}
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Mã SV</th>
                            <th>Họ tên</th>
                            <th>Lớp</th>
                            <th>Khoa</th>
                            <th>Thời gian đăng ký</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            students.forEach((s, i) => {
                html += `
                    <tr>
                        <td>${i + 1}</td>
                        <td>${s.msv ?? '—'}</td>
                        <td>${s.full_name ?? ''}</td>
                        <td>${s.class ?? ''}</td>
                        <td>${s.faculty ?? ''}</td>
                        <td>${new Date(s.register_date).toLocaleString('vi-VN')}</td>
                    </tr>
                `;
            });
            html += `</tbody></table>`;
            listDiv.innerHTML = html;

            attachFilters(eventId); // ✅ luôn gắn filter sau khi render mới

        } catch (err) {
            console.error("🔥 FETCH ERROR:", err);
            listDiv.innerHTML = `<p class="text-danger text-center">❌ Lỗi tải dữ liệu: ${err.message}</p>`;
        }
    }

    // Hàm gắn filter có kiểm tra để không bị chồng event
    function attachFilters(eventId) {
        const filterClass = document.getElementById('filterClass');
        const filterFaculty = document.getElementById('filterFaculty');

        if (!filterClass || !filterFaculty) return;

        // ✅ Xóa event cũ trước khi gắn mới
        filterClass.onchange = null;
        filterFaculty.onchange = null;

        // ✅ Gắn sự kiện lọc
        filterClass.addEventListener('change', e => {
            const selectedClass = e.target.value;
            const selectedFaculty = filterFaculty.value;
            loadList(eventId, selectedClass, selectedFaculty);
        });

        filterFaculty.addEventListener('change', e => {
            const selectedFaculty = e.target.value;
            const selectedClass = filterClass.value;
            loadList(eventId, selectedClass, selectedFaculty);
        });
    }
});
</script>

{{-- Styles nho nhỏ cho layout --}}
<style>
    .ratio .object-fit-cover {
        object-fit: cover;
    }

    .chip {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        color: #374151;
        padding: .35rem .6rem;
        border-radius: 999px;
        font-size: .875rem
    }

    .info {
        background: #fafafa;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: .75rem .9rem
    }

    .info-label {
        font-size: .85rem;
        color: #6b7280
    }

    .info-value {
        font-weight: 600;
        color: #374151
    }

    .bg-success-subtle {
        background: #e8f6ee !important
    }

    .bg-primary-subtle {
        background: #eef2ff !important
    }

    .bg-secondary-subtle {
        background: #f1f2f4 !important
    }
</style>


@endsection