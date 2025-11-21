{{-- resources/views/students/import.blade.php --}}
@extends('layouts.app')
@section('title','Import sinh viên')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: #fff;
        border-radius: 18px;
    }

    .card-modern {
        border: 0;
        border-radius: 18px
    }

    .required::after {
        content: " *";
        color: #dc3545;
        font-weight: 600
    }

    .sticky-actions {
        position: sticky;
        bottom: 0;
        background: #ffffffcc;
        backdrop-filter: blur(4px);
        border-top: 1px solid #eef2f7;
        padding: .75rem;
        border-bottom-left-radius: 18px;
        border-bottom-right-radius: 18px;
    }

    .mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace
    }

    .hint {
        color: #64748b
    }

    .btn-soft {
        background: #f1f5f9;
        border-color: #f1f5f9
    }

    .btn-soft:hover {
        background: #e2e8f0
    }
</style>
@endpush

@section('content')

<div class="container py-4" style="max-width:840px">
    {{-- Header --}}
    <div class="page-header p-4 mb-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <div class="small opacity-75">Quản lý sinh viên</div>
                <h4 class="mb-1">Import sinh viên từ Excel</h4>
                <div class="small">
                    <a href="{{ route('students.index') }}" class="text-white text-decoration-underline">Danh sách</a>
                    <span class="mx-1">/</span>
                    <span class="opacity-75">Import</span>
                </div>
            </div>
            <a href="{{ route('students.index') }}" class="btn btn-light btn-sm rounded-pill px-3">← Quay lại</a>
        </div>
    </div>

    {{-- Hướng dẫn & thông báo --}}
    <div class="card card-modern shadow-sm mb-3">
        <div class="card-body p-4">
            <div class="alert alert-info mb-3">
                <div class="fw-semibold mb-2">📘 Định dạng file (.xlsx/.xls) – có dòng tiêu đề:</div>
                <pre class="mb-2 mono">MaSv, full_name, email, phone, class, faculty</pre>
                <div class="small mb-3">
                    • <b>MaSv</b>: Mã sinh viên (bắt buộc, duy nhất). <br>
                    • <b>full_name</b>: Họ tên sinh viên. <br>
                    • Các cột khác có thể để trống nếu chưa có dữ liệu.<br>
                    • Tài khoản mới sẽ có mật khẩu mặc định: <code>12345678</code>.
                </div>

                {{-- ✅ Thêm nút tải file mẫu ngay tại đây --}}
                @if (Route::has('students.import.sample'))
                <a href="{{ route('students.import.sample') }}" class="btn btn-sm btn-outline-primary rounded-pill fw-semibold">
                    ⬇️ Tải file mẫu Excel
                </a>

                @endif
            </div>

            {{-- Hiển thị lỗi import nếu có --}}
            @if (session('import_errors'))
            <div class="alert alert-warning mb-0">
                <div class="fw-semibold mb-1">Một số dòng bị bỏ qua:</div>
                <ul class="mb-0">
                    @foreach (session('import_errors') as $err)
                    <li class="mono">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('students.import.store') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="card card-modern shadow-sm">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label required">Chọn file</label>
                    <input
                        type="file"
                        name="file"
                        class="form-control @error('file') is-invalid @enderror"
                        accept=".xlsx,.xls,.csv" required>
                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text hint">Hỗ trợ: .xlsx, .xls</div>
                </div>
            </div>

            {{-- Thanh hành động --}}
            <div class="sticky-actions d-flex justify-content-end gap-2">
                <a href="{{ route('students.index') }}" class="btn btn-soft rounded-pill px-3">Huỷ</a>
                <button class="btn btn-primary rounded-pill px-4" type="submit">Import</button>
            </div>
        </div>
    </form>
</div>

@endsection