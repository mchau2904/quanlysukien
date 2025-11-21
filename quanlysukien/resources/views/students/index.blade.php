@extends('layouts.app')
@section('title', 'Quản lý sinh viên')

@section('content')
<div class="page-wrapper d-flex flex-column min-vh-100">
    <main>
        <div class="container px-5 pt-5">
            {{-- 🔹 Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h3 class="fw-bold text-primary mb-0">
                    <i class="bi bi-people me-2"></i>Quản lý Sinh viên
                </h3>

                {{-- 🔍 Form tìm kiếm riêng --}}
                <form method="GET" action="{{ route('students.index') }}" class="d-flex" style="max-width:320px;">
                    <input type="text" name="q" value="{{ $q }}" class="form-control me-2" placeholder="Tìm tên hoặc MSSV...">
                    <button class="btn btn-outline-secondary">Tìm</button>
                </form>

                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <a href="{{ route('students.create') }}" class="btn btn-gradient rounded-pill">
                        <i class="bi bi-person-plus me-1"></i> Thêm mới
                    </a>
                    <a href="{{ route('students.import.form') }}" class="btn btn-gradient rounded-pill">
                        <i class="bi bi-file-earmark-arrow-up me-1"></i> Import Excel
                    </a>
                   <a href="{{ route('students.export', request()->query()) }}" 
                    class="btn btn-gradient rounded-pill">
                        <i class="bi bi-file-earmark-arrow-down me-1"></i> Export Excel
                    </a>

                </div>
            </div>

            {{-- 🧮 Bộ lọc theo khoa, lớp, sắp xếp --}}
            <form method="GET" action="{{ route('students.index') }}" class="d-flex flex-wrap gap-2 mb-4">
                {{-- Giữ nguyên tham số tìm kiếm nếu có --}}
                <input type="hidden" name="q" value="{{ $q }}">

                <select name="faculty" class="form-select" style="max-width:200px;">
                    <option value="">-- Khoa --</option>
                    @foreach($faculties as $f)
                        <option value="{{ $f }}" @selected($faculty == $f)>{{ $f }}</option>
                    @endforeach
                </select>

                <select name="class" class="form-select" style="max-width:200px;">
                    <option value="">-- Lớp --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c }}" @selected($class == $c)>{{ $c }}</option>
                    @endforeach
                </select>

                <select name="sort" class="form-select" style="max-width:180px;">
                    <option value="desc" @selected($sort == 'desc')>Sự kiện ↓</option>
                    <option value="asc" @selected($sort == 'asc')>Sự kiện ↑</option>
                </select>

                <button class="btn btn-outline-primary">Lọc</button>
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Đặt lại</a>
            </form>

            {{-- 🔹 Danh sách --}}
            <div class="card p-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">Danh sách sinh viên</h5>
                    <form id="bulkDeleteForm" action="{{ route('students.bulkDelete') }}" method="POST"
                        onsubmit="return confirm('Bạn có chắc muốn xoá các sinh viên đã chọn không?')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ids" id="selectedIds">
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill" id="btnDeleteSelected" disabled>
                            <i class="bi bi-trash3"></i> Xoá đã chọn
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0" id="studentsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="5%"><input type="checkbox" id="selectAll"></th>
                                <th>STT</th>
                                <th>MSSV</th>
                                <th>Họ tên</th>
                                <th>Lớp</th>
                                <th>Khoa</th>
                                <th>Giới tính</th>
                                <th>
                                    <a href="{{ route('students.index', array_merge(request()->except('sort'), ['sort' => $sort == 'asc' ? 'desc' : 'asc'])) }}"
                                       class="text-decoration-none text-dark">
                                        Tổng sự kiện đã tham gia {!! $sort == 'asc' ? '↑' : '↓' !!}
                                    </a>
                                </th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $st)
                            <tr>
                                <td><input type="checkbox" class="student-checkbox" value="{{ $st->user_id }}"></td>
                                <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                                <td>{{ $st->username }}</td>
                                <td>{{ $st->full_name }}</td>
                                <td>{{ $st->class ?? '—' }}</td>
                                <td>{{ $st->faculty ?? '—' }}</td>
                                <td>{{ $st->gender ?? '—' }}</td>
                                <td><strong>{{ $st->total_events }}</strong></td>
                                <td class="text-center">
                                   <a href="{{ route('students.show', $st->user_id) }}" 
                                    class="btn btn-sm btn-outline-primary me-1" 
                                    title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                    </a>

                                    <a href="{{ route('students.edit', $st->user_id) }}" class="btn btn-sm btn-outline-warning me-1" title="Chỉnh sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                         </a>
                                    <form action="{{ route('students.destroy', $st->user_id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Xoá sinh viên này?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Xoá">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="text-center text-muted py-4">Chưa có sinh viên nào.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $students->links() }}</div>
            </div>
        </div>
    </main>
</div>

{{-- JS chọn xoá nhiều --}}
<script>
const selectAll = document.getElementById('selectAll');
const checkboxes = document.querySelectorAll('.student-checkbox');
const btnDeleteSelected = document.getElementById('btnDeleteSelected');
const selectedIdsInput = document.getElementById('selectedIds');

selectAll?.addEventListener('change', function() {
    checkboxes.forEach(cb => cb.checked = this.checked);
    updateSelection();
});
checkboxes.forEach(cb => cb.addEventListener('change', updateSelection));

function updateSelection() {
    const selectedIds = Array.from(checkboxes)
        .filter(cb => cb.checked)
        .map(cb => cb.value);
    selectedIdsInput.value = selectedIds.join(',');
    btnDeleteSelected.disabled = selectedIds.length === 0;
}
</script>

<style>
/* 🌈 Toàn trang */
body {
    background: #f8fafc;
    color: #1e293b;
    font-family: "Inter", system-ui, sans-serif;
}


    /* .table>thead {
        vertical-align: middle;
    }

    .table>thead tr th{
        vertical-align: middle;
        text-align: center
    } */
/* 📘 Header chính */
h3.fw-bold {
    font-weight: 700;
    background: linear-gradient(90deg, #3b82f6, #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: flex;
    align-items: center;
    gap: 8px;
}

h5.fw-semibold {
    color: #1e293b;
}

/* 🔍 Thanh tìm kiếm */
form.d-flex input.form-control {
    border-radius: 30px;
    padding-left: 14px;
    border: 1px solid #e2e8f0;
}

form.d-flex button {
    border-radius: 30px;
    font-weight: 500;
}

/* ⚙️ Bộ lọc */
form.d-flex.flex-wrap {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    padding: 12px 18px;
}

form.d-flex.flex-wrap select,
form.d-flex.flex-wrap button,
form.d-flex.flex-wrap a {
    border-radius: 10px;
}

/* 🧮 Card chính */
.card {
    background: #fff;
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    border: 1px solid #eef2f7;
}

.card h5 {
    font-weight: 600;
    color: #1e293b;
}

/* 🧭 Nút gradient */
.btn-gradient {
    background: linear-gradient(90deg, #3b82f6, #6366f1);
    color: #fff;
    border: none;
    transition: all 0.25s ease;
    font-weight: 500;
}
.btn-gradient:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(99, 102, 241, 0.25);
    opacity: 0.95;
}

/* 🔹 Bảng dữ liệu */
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
    white-space: nowrap;
}
.table td {
    vertical-align: middle;
    color: #475569;
    font-size: 0.95rem;
}

.table tbody tr:hover {
    background-color: #f1f5f9;
    transition: 0.2s;
}

/* 🧾 Checkbox + Hành động */
input[type="checkbox"] {
    cursor: pointer;
    transform: scale(1.1);
}
.btn-sm {
    padding: 4px 8px;
    border-radius: 8px;
}
.btn-outline-primary {
    border-color: #6366f1;
    color: #6366f1;
}
.btn-outline-primary:hover {
    background-color: #6366f1;
    color: #fff;
}
.btn-outline-warning:hover {
    background-color: #facc15;
    border-color: #facc15;
    color: #000;
}
.btn-outline-danger:hover {
    background-color: #ef4444;
    border-color: #ef4444;
    color: #fff;
}

/* 🧰 Nút Xoá nhiều */
#btnDeleteSelected {
    font-size: 0.9rem;
    transition: 0.25s;
}
#btnDeleteSelected:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* 📊 Phân trang */
.pagination {
    justify-content: center;
}
.page-link {
    border-radius: 50%;
    margin: 0 3px;
    color: #3b82f6;
}
.page-item.active .page-link {
    background: linear-gradient(90deg, #3b82f6, #6366f1);
    border: none;
}
.page-link:hover {
    background-color: #e0e7ff;
}

/* 💎 Tooltip & icon */
i.fa-solid {
    transition: 0.25s;
}
.btn:hover i.fa-solid {
    transform: scale(1.1);
}

/* 🌟 Responsive tối ưu */
@media (max-width: 768px) {
    .d-flex.flex-wrap.gap-2 {
        flex-direction: column;
    }
    form.d-flex {
        width: 100%;
        flex-direction: column;
        gap: 8px;
    }
    form.d-flex input.form-control {
        width: 100%;
    }
}
</style>

@endsection
