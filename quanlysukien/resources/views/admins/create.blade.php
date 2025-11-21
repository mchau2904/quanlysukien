@extends('layouts.app')
@section('title', 'Thêm cán bộ')

@section('content')
<div class="container mt-4" style="max-width:700px;">
    <h4 class="fw-bold mb-3">➕ Thêm cán bộ</h4>

    <form action="{{ route('admins.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Mã cán bộ</label>
                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Mật khẩu <small class="text-muted">(bỏ trống = 12345678)</small></label>
                <input type="password" name="password" class="form-control">
            </div>

        </div>

        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Giới tính</label>
                <select name="gender" class="form-select">
                    <option value="">-- Chọn --</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Chức vụ</label>
                <input type="text" name="faculty" class="form-control" value="{{ old('faculty') }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
    </form>
</div>
@endsection