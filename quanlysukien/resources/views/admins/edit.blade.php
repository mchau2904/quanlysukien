@extends('layouts.app')
@section('title', 'Chỉnh sửa cán bộ')

@section('content')
<div class="container mt-4" style="max-width:700px;">
    <h4 class="fw-bold mb-3">✏️ Chỉnh sửa cán bộ</h4>

    <form action="{{ route('admins.update', $admin->user_id) }}" method="POST" class="card p-4 shadow-sm">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" value="{{ $admin->username }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Họ tên</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $admin->full_name) }}" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Ngày sinh</label>
                <input type="date" name="dob" class="form-control" value="{{ old('dob', $admin->dob) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Giới tính</label>
                <select name="gender" class="form-select">
                    <option value="">-- Chọn --</option>
                    <option value="Nam" {{ $admin->gender === 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $admin->gender === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Chức vụ</label>
                <input type="text" name="faculty" class="form-control" value="{{ old('faculty', $admin->faculty) }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $admin->phone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}">
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('admins.index') }}" class="btn btn-outline-secondary">← Quay lại</a>
            <button type="submit" class="btn btn-success">Cập nhật</button>
        </div>
    </form>
</div>
@endsection