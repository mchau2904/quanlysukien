{{-- resources/views/students/edit.blade.php --}}
@extends('layouts.app')
@section('title','Sửa sinh viên')
@push('styles')
<style>
body {
    background: #f8fafc;
    font-family: 'Inter', system-ui, sans-serif;
}

/* 🌈 Header tiêu đề */
h4.mb-3 {
    font-weight: 700;
    color: #1e293b;
    padding-bottom: 0.75rem;
    border-left: 6px solid #6366f1;
    padding-left: 12px;
}

/* 📋 Card bao quanh form */
.container form {
    background: #ffffff;
    border-radius: 16px;
    padding: 32px 36px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
    border: 1px solid #eef2f7;
}

/* 🧩 Label và input */
.form-label {
    font-weight: 500;
    color: #475569;
}
.form-control,
.form-select {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    transition: 0.25s ease;
    font-size: 0.95rem;
    padding: 10px 12px;
}
.form-control:focus,
.form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.15rem rgba(99, 102, 241, 0.25);
}

/* 🧾 Nút hành động */
.btn-primary {
    background: linear-gradient(135deg, #6366f1, #06b6d4);
    border: none;
    font-weight: 600;
    border-radius: 50px;
    padding: 8px 24px;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
    transition: 0.3s;
}
.btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.btn-light {
    background: #f1f5f9;
    border-radius: 50px;
    color: #334155;
    font-weight: 500;
}
.btn-light:hover {
    background: #e2e8f0;
}

/* 🧮 Khoảng cách giữa các input */
.row.g-3 > div {
    margin-bottom: 8px;
}

/* ⚠️ Thông báo lỗi */
.text-danger.small {
    margin-top: 4px;
    font-size: 0.85rem;
}

/* ✨ Hiệu ứng hover nhỏ trên card */
.container form:hover {
    box-shadow: 0 6px 22px rgba(99, 102, 241, 0.1);
    transition: box-shadow 0.3s ease;
}

/* 📱 Responsive */
@media (max-width: 768px) {
    .container form {
        padding: 20px;
    }
    h4.mb-3 {
        font-size: 1.25rem;
    }
}
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width:720px">
    <h4 class="mb-3">Sửa sinh viên: {{ $user->username }}</h4>

    <form method="POST" action="{{ route('students.update', $user->user_id) }}">
        @csrf @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Họ tên</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="form-control" required>
                @error('full_name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="dob" value="{{ old('dob', $user->dob) }}" class="form-control">
                @error('dob')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Giới tính</label>
                <select name="gender" class="form-select">
                    <option value="">-- Chọn --</option>
                    @foreach (['Nam','Nữ','Khác'] as $g)
                    <option value="{{ $g }}" {{ old('gender', $user->gender)===$g ? 'selected':'' }}>{{ $g }}</option>
                    @endforeach
                </select>
                @error('gender')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">SĐT</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control">
                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Lớp</label>
                <input type="text" name="class" value="{{ old('class', $user->class) }}" class="form-control">
                @error('class')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="faculty" class="form-label required">Khoa</label>
                <select name="faculty" id="faculty" class="form-select @error('faculty') is-invalid @enderror" required>
                    <option value="">-- Chọn khoa --</option>
                    @foreach ([
                    'Công nghệ thông tin',
                    'Kế toán',
                    'Ngân hàng',
                    'Tài chính',
                    'Chất lượng cao',
                    'Khác',
                    'Tất cả'
                    ] as $facultyOption)
                    <option value="{{ $facultyOption }}"
                        {{ old('faculty', $user->faculty) === $facultyOption ? 'selected' : '' }}>
                        {{ $facultyOption }}
                    </option>
                    @endforeach
                </select>
                @error('faculty')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Cập nhật</button>
            <a href="{{ route('students.index') }}" class="btn btn-light">Huỷ</a>
        </div>
    </form>
</div>
@endsection