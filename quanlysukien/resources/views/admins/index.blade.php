@extends('layouts.app')
@section('title', 'Quản lý Cán bộ')
@push('styles')
<style>
body {
    background: #f8fafc;
    font-family: 'Inter', system-ui, sans-serif;
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
    margin-bottom: 1.5rem;
}

/* 🔍 Thanh công cụ */
.d-flex.justify-content-between {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 20px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}

form.d-flex input.form-control {
    border-radius: 50px;
    padding: 10px 14px;
    border: 1px solid #e2e8f0;
}
form.d-flex input.form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.15rem rgba(99, 102, 241, 0.2);
}

form.d-flex button {
    border-radius: 50px;
    font-weight: 500;
    background: linear-gradient(135deg, #6366f1, #06b6d4);
    border: none;
}
form.d-flex button:hover {
    opacity: 0.9;
}

/* 🧩 Nút Import / Thêm mới */
.btn-outline-primary,
.btn-success {
    border-radius: 50px;
    font-weight: 500;
    padding: 8px 18px;
    transition: all 0.25s ease;
}
.btn-outline-primary {
    border-color: #6366f1;
    color: #6366f1;
}
.btn-outline-primary:hover {
    background: #6366f1;
    color: #fff;
}
.btn-success {
    /* background: linear-gradient(135deg, #22c55e, #16a34a);s */
    background: linear-gradient(90deg, #3b82f6, #6366f1);
    border: none;
    color: #fff;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
}
.btn-success:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* 🧾 Card & bảng */
.card {
    border: none;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    border: 1px solid #eef2f7;
}

.table {
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    margin-bottom: 0;
}

.table thead {
    background: #f9fafb;
    border-bottom: 2px solid #e2e8f0;
}
.table th {
    font-weight: 600;
    color: #334155;
    font-size: 0.95rem;
}
.table td {
    color: #475569;
    font-size: 0.95rem;
}
.table tbody tr:hover {
    background: #f1f5f9;
    transition: background 0.2s ease;
}

/* 🧰 Nút hành động */
.btn-sm {
    border-radius: 8px;
    padding: 5px 9px;
}
.btn-outline-warning {
    color: #f59e0b;
    border-color: #f59e0b;
}
.btn-outline-warning:hover {
    background: #facc15;
    border-color: #facc15;
    color: #000;
}
.btn-outline-danger {
    color: #ef4444;
    border-color: #ef4444;
}
.btn-outline-danger:hover {
    background: #ef4444;
    border-color: #ef4444;
    color: #fff;
}

/* 📊 Phân trang */
.pagination {
    justify-content: center;
    margin-top: 1rem;
}
.page-link {
    border-radius: 50%;
    margin: 0 3px;
    color: #6366f1;
}
.page-item.active .page-link {
    background: linear-gradient(135deg, #6366f1, #06b6d4);
    border: none;
}
.page-link:hover {
    background-color: #e0e7ff;
}

/* 🧠 Hiệu ứng nhỏ */
.card:hover {
    box-shadow: 0 6px 22px rgba(99, 102, 241, 0.1);
    transition: 0.3s ease;
}
i.fa-solid {
    transition: 0.2s ease;
}
.btn:hover i.fa-solid {
    transform: scale(1.15);
}

/* 📱 Responsive */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 12px;
    }
    .d-flex.justify-content-between form.d-flex {
        width: 100%;
    }
    .d-flex.justify-content-between .d-flex.gap-2 {
        justify-content: space-between;
        flex-wrap: wrap;
    }
}
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h4 class="fw-bold mb-3 text-primary">Danh sách Cán bộ (Admin)</h4>

    {{-- Thanh công cụ --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- Form tìm kiếm --}}
        <form method="GET" class="d-flex" style="max-width:400px;">
            <input type="text" name="q" class="form-control me-2" placeholder="🔍 Tìm theo tên hoặc username..."
                value="{{ request('q') }}">
            <button class="btn btn-primary">Tìm</button>
        </form>

        <div class="d-flex gap-2">
            <a href="{{ route('admins.import') }}" class="btn btn-outline-primary rounded-pill">
                📥 Import Excel
            </a>
            <a href="{{ route('admins.create') }}" class="btn btn-success rounded-pill">
                ➕ Thêm cán bộ
            </a>
        </div>
    </div>


    {{-- Bảng danh sách --}}
    <div class="card shadow-sm">
        <table class="table table-bordered align-middle text-center mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Mã cán bộ</th>
                    <th>Họ tên</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Chức vụ</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $index => $a)
                <tr>
                    <td>{{ $admins->firstItem() + $index }}</td>
                    <td>{{ $a->username }}</td>
                    <td>{{ $a->full_name }}</td>
                    <td>{{ $a->dob ?? '-' }}</td>
                    <td>{{ $a->gender ?? '-' }}</td>
                    <td>{{ $a->phone ?? '-' }}</td>
                    <td>{{ $a->email ?? '-' }}</td>
                    <td>{{ $a->faculty ?? '-' }}</td>
                    <td>
                        <div class="d-flex justify-content-center gap-1">
                            <a href="{{ route('admins.edit', $a->user_id) }}"
                                class="btn btn-sm btn-outline-warning" title="Sửa">
                                <i class="fa-solid fa-pencil"></i>
                            </a>

                            @if($a->user_id != 1)
                            <form action="{{ route('admins.destroy', $a->user_id) }}" method="POST"
                                onsubmit="return confirm('Xoá cán bộ này?')" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Xoá">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-muted">Chưa có cán bộ nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-3 d-flex justify-content-center">
        {{ $admins->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection