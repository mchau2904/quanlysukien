{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')
@push('styles')
<style>
body {
    background: #f8fafc;
    font-family: "Inter", system-ui, sans-serif;
    color: #1e293b;
}

/* 🌈 Tiêu đề */
h4.mb-3 {
    font-weight: 700;
    color: #1e293b;
    padding-left: 12px;
    border-left: 6px solid #6366f1;
    margin-bottom: 1.5rem;
}

/* 🧾 Form bao quanh */
.container form {
    background: #fff;
    border-radius: 16px;
    padding: 36px 40px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
    border: 1px solid #eef2f7;
}

/* 🧩 Label & input */
.form-label {
    font-weight: 500;
    color: #475569;
}
.form-control,
.form-select {
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    font-size: 0.95rem;
    padding: 10px 12px;
    transition: 0.25s;
}
.form-control:focus,
.form-select:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 0.15rem rgba(99, 102, 241, 0.25);
}

/* 🔒 Input readonly / disabled */
.form-control[readonly],
.form-select[disabled] {
    background: #f8fafc;
    color: #64748b;
    cursor: not-allowed;
    opacity: 0.85;
}

/* ✅ Thông báo */
.alert-success {
    background: #dcfce7;
    color: #166534;
    border-radius: 12px;
    border: none;
    padding: 10px 16px;
    font-weight: 500;
}

/* 🧮 Button hành động */
.btn-primary {
    background: linear-gradient(135deg, #6366f1, #06b6d4);
    border: none;
    border-radius: 50px;
    padding: 8px 24px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
    transition: 0.25s;
}
.btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
.btn-light {
    background: #f1f5f9;
    border-radius: 50px;
    font-weight: 500;
    color: #334155;
}
.btn-light:hover {
    background: #e2e8f0;
}

/* ⚠️ Lỗi input */
.text-danger.small {
    margin-top: 4px;
    font-size: 0.85rem;
}

/* ✨ Hover nhẹ trên card */
.container form:hover {
    box-shadow: 0 6px 22px rgba(99, 102, 241, 0.1);
    transition: 0.3s ease;
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
<div class="container" style="max-width:720px">
    <h4 class="mb-3">Thay đổi thông tin</h4>

    @if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                {{-- ✅ Nếu là admin hiển thị "Mã cán bộ", ngược lại "Mã sinh viên (MSSV)" --}}
                <label class="form-label">
                    {{ $user->role === 'admin' ? 'Mã cán bộ' : 'Mã sinh viên (MSSV)' }}
                </label>
                <input type="text" name="username"
                    value="{{ old('username', $user->username) }}"
                    class="form-control" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Họ và tên</label>
                <input type="text" name="full_name"
                    value="{{ old('full_name', $user->full_name) }}"
                    class="form-control" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="dob"
                    value="{{ old('dob', $user->dob) }}"
                    class="form-control" readonly>
            </div>

            <div class="col-md-4">
                <label class="form-label">Giới tính</label>
                <select name="gender" class="form-select" disabled>
                    <option value="">-- Chọn --</option>
                    @foreach (['Nam','Nữ','Khác'] as $g)
                    <option value="{{ $g }}" {{ old('gender',$user->gender)===$g?'selected':'' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">SĐT</label>
                <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control">
            </div>

            {{-- ✅ Nếu là admin: đổi nhãn "Lớp" → "Chức vụ", "Khoa" → "Phòng/Ban" --}}
           {{-- Hiển thị trường này chỉ khi KHÔNG phải admin --}}
            @if($user->role !== 'admin')
            <div class="col-md-6">
                <label class="form-label">Lớp</label>
                <input type="text" name="class"
                    value="{{ old('class', $user->class) }}"
                    class="form-control" readonly>
            </div>
            @endif


            <div class="col-md-6">
                <label class="form-label">
                    {{ $user->role === 'admin' ? 'Chức vụ' : 'Khoa' }}
                </label>
                <input type="text" name="faculty"
                    value="{{ old('faculty',$user->faculty) }}"
                    class="form-control" readonly>
            </div>

            <div class="col-12">
                <label class="form-label">Mật khẩu mới (tuỳ chọn)</label>
                <input type="password" name="password" class="form-control" placeholder="Để trống nếu không đổi">
            </div>
        </div>

        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
            <a href="/" class="btn btn-light">Huỷ</a>
        </div>
    </form>

</div>
@endsection
