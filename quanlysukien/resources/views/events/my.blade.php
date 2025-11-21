@extends('layouts.app')
@section('title', 'Sự kiện của tôi')
@push('styles')
<style>
body {
    background: #f8fafc;
    font-family: "Inter", system-ui, sans-serif;
    color: #1e293b;
}

.table>thead {
    vertical-align: middle;
}

.table>thead tr th{
    vertical-align: middle;
    text-align: center
}
/* 🌈 Tiêu đề trang */
h4.fw-bold {
    font-weight: 700;
    color: #1e293b;
    padding-left: 12px;
    border-left: 6px solid #6366f1;
}

/* 🔍 Thanh tìm kiếm */
.input-group input.form-control {
    border: 1px solid #e2e8f0;
    border-right: none;
    border-radius: 50px 0 0 50px;
    padding: 8px 14px;
    font-size: 0.95rem;
    transition: 0.25s ease;
}
.input-group input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.15rem rgba(99, 102, 241, 0.25);
}
.input-group .btn-primary {
    border-radius: 0 50px 50px 0;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border: none;
    font-weight: 500;
    transition: 0.25s;
}
.input-group .btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* 🧭 Nút quay lại */
.btn-light {
    border-radius: 50px;
    background: #f1f5f9;
    color: #334155;
    font-weight: 500;
}
.btn-light:hover {
    background: #e2e8f0;
}

/* 📋 Thông báo từ khóa */
.text-muted.small a {
    color: #6366f1;
}
.text-muted.small a:hover {
    text-decoration: underline;
}

/* 🧾 Card chứa bảng */
.card {
    border: none;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    border: 1px solid #eef2f7;
}

/* 🧮 Bảng dữ liệu */
.table {
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}
.table thead {
    background-color: #f9fafb;
    border-bottom: 2px solid #e2e8f0;
}
.table th {
    font-weight: 600;
    color: #334155;
    font-size: 0.95rem;
}
.table td {
    color: #475569;
    vertical-align: middle;
    font-size: 0.95rem;
}
.table tbody tr:hover {
    background-color: #f1f5f9;
    transition: 0.2s ease;
}

/* 🎯 Nút hành động */
.btn-sm {
    border-radius: 30px;
    padding: 5px 12px;
    font-size: 0.85rem;
    font-weight: 500;
}
.btn-outline-info {
    border-color: #0ea5e9;
    color: #0ea5e9;
}
.btn-outline-info:hover {
    background-color: #0ea5e9;
    color: #fff;
}
.btn-primary {
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    border: none;
}
.btn-primary:hover {
    opacity: 0.9;
}
.btn-success {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border: none;
}
.btn-success:hover {
    opacity: 0.9;
}

/* ⚠️ Trạng thái rỗng */
.text-muted.py-4 {
    font-style: italic;
    font-size: 0.95rem;
    background: #fafafa;
    border-radius: 12px;
}

/* ✨ Hiệu ứng nhỏ */
.card:hover {
    box-shadow: 0 6px 22px rgba(99, 102, 241, 0.1);
    transition: 0.3s ease;
}

/* 📱 Responsive */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 10px;
    }
    form.d-flex {
        width: 100%;
    }
}
</style>
@endpush

@section('content')

<div class="d-flex flex-column">
    <div class="container py-4">

        {{-- === Header + Tìm kiếm === --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
            <h4 class="fw-bold m-0">
                <i class="bi bi-list-check me-2"></i>Sự kiện đã đăng ký
            </h4>

            {{-- 🔍 Form tìm kiếm --}}
            <form method="GET" action="{{ route('registrations.mine') }}" class="d-flex align-items-center mt-2 mt-sm-0" style="max-width: 350px;">
                <div class="input-group input-group-sm">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        class="form-control rounded-start-pill"
                        placeholder="🔍 Tìm theo tên sự kiện...">
                    <button class="btn btn-primary rounded-end-pill" type="submit">Tìm</button>
                </div>
            </form>

            <a href="{{ route('events.index') }}" class="btn btn-light ms-2 mt-2 mt-sm-0">← Quay lại danh sách</a>
        </div>

        {{-- ✅ Thông báo từ khóa tìm kiếm --}}
        @if(request('q'))
        <div class="text-muted small mb-3">
            Kết quả cho: <strong>{{ request('q') }}</strong>
            <a href="{{ route('registrations.mine') }}" class="ms-2 text-decoration-none">✖ Xóa tìm kiếm</a>
        </div>
        @endif

        {{-- === Danh sách sự kiện === --}}
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Tên sự kiện</th>
                            <th>Thời gian</th>
                            <th>Địa điểm</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($regs as $r)
                        @php $e = $r->event; @endphp
                        <tr>
                            <td>{{ $e->event_code ?? $e->event_id }}</td>
                            <td class="fw-semibold">{{ $e->event_name }}</td>
                            <td>
                                {{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d/m/Y H:i') }}
                                –
                                {{ \Illuminate\Support\Carbon::parse($e->end_time)->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ $e->location ?? '—' }}</td>
                            <td class="text-center">
                                <a href="{{ route('events.show', $e->event_id) }}" class="btn btn-sm btn-outline-info me-2">
                                    Chi tiết
                                </a>
                                <a href="{{ route('attendance.form', ['event_id' => $e->event_id]) }}" class="btn btn-sm btn-primary">
                                    Điểm danh
                                </a>
                                @if(\Carbon\Carbon::parse($e->end_time)->isPast())
                                <a href="{{ route('evaluations.show', ['event_id' => $e->event_id]) }}" class="btn btn-sm btn-success">
                                    ⭐ Đánh giá
                                </a>
                                @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                @if(request('q'))
                                Không tìm thấy sự kiện nào phù hợp với từ khóa "{{ request('q') }}".
                                @else
                                Bạn chưa đăng ký sự kiện nào.
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
    .input-group input:focus {
        box-shadow: none;
        border-color: #3b82f6;
    }

    .btn-primary {
        background: linear-gradient(90deg, #3b82f6, #6366f1);
        border: none;
    }

    .btn-primary:hover {
        opacity: 0.9;
    }
</style>

@endsection