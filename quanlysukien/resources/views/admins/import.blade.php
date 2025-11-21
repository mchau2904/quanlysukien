@extends('layouts.app')
@section('title','Import cán bộ')

@section('content')
<div class="container py-4" style="max-width:840px">
    {{-- Header --}}
    <div class="page-header p-4 mb-4 shadow-sm bg-primary text-white rounded-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">📥 Import Cán bộ từ Excel</h4>
                <small class="opacity-75">Quản lý cán bộ / Import</small>
            </div>
            <a href="{{ route('admins.index') }}" class="btn btn-light btn-sm rounded-pill px-3">← Quay lại</a>
        </div>
    </div>

    {{-- Card nội dung --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            {{-- Hướng dẫn --}}
            <div class="alert alert-info mb-3">
                <div class="fw-semibold mb-2">📘 Cấu trúc file Excel (bắt buộc có dòng tiêu đề):</div>
                <pre class="mono mb-2">Mã cán bộ, Họ tên, Ngày sinh, SĐT, Email, Chức vụ</pre>
                <small class="text-muted d-block mb-2">
                    • Mật khẩu mặc định: <code>12345678</code><br>
                    • Tài khoản mới tự động có vai trò <strong>admin</strong>.
                </small>

                {{-- ✅ Nút tải file mẫu --}}
                <a href="{{ route('admins.import.sample') }}"
                    class="btn btn-sm btn-outline-primary rounded-pill fw-semibold">
                    ⬇️ Tải file mẫu Excel
                </a>
            </div>

            {{-- Hiển thị lỗi import nếu có --}}
            @if (session('import_errors'))
            <div class="alert alert-warning">
                <div class="fw-semibold mb-2">⚠️ Một số dòng bị bỏ qua:</div>
                <ul class="mb-0">
                    @foreach (session('import_errors') as $err)
                    <li class="mono">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Form upload file --}}
            <form method="POST" action="{{ route('admins.import.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold required">Chọn file Excel</label>
                    <input type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        class="form-control @error('file') is-invalid @enderror"
                        required>
                    @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted">
                        Hỗ trợ định dạng: .xlsx, .xls, .csv — tối đa 20MB
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary rounded-pill">Huỷ</a>
                    <button type="submit" class="btn btn-primary rounded-pill">
                        📤 Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection