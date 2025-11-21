{{-- resources/views/students/create.blade.php --}}
@extends('layouts.app')

@section('title','Thêm sinh viên')

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: #fff;
        border-radius: 18px;
    }

    .card-modern {
        border: 0;
        border-radius: 18px;
    }

    .required::after {
        content: " *";
        color: #dc3545;
        font-weight: 600;
    }

    .hint {
        color: #6c757d;
    }

    .btn-soft {
        background: #f1f5f9;
        border-color: #f1f5f9;
    }

    .btn-soft:hover {
        background: #e2e8f0;
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

    .form-floating>label>small {
        opacity: .8
    }

    .section-title {
        font-weight: 600;
        font-size: .95rem;
        color: #0f172a;
       margin-bottom: 0;
    }
</style>
@endpush

@section('content')

<div class="container py-4" style="max-width:840px">
    <div class="page-header p-4 mb-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <div class="small opacity-75">Quản lý sinh viên</div>
                <h4 class="mb-1">Thêm sinh viên mới</h4>
                <div class="small">
                    <a href="{{ route('students.index') }}" class="text-white text-decoration-underline">Danh sách</a>
                    <span class="mx-1">/</span>
                    <span class="opacity-75">Tạo mới</span>
                </div>
            </div>
            <a href="{{ route('students.index') }}" class="btn btn-light btn-sm rounded-pill px-3">
                ← Quay lại
            </a>
        </div>
    </div>

    {{-- Form Card --}}
    <form method="POST" action="{{ route('students.store') }}">
        @csrf

        <div class="card card-modern shadow-sm">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="section-title" >Thông tin tài khoản</div>
                    </div>

                    {{-- MSSV (username) --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="username" id="username"
                                value="{{ old('username') }}"
                                class="form-control @error('username') is-invalid @enderror"
                                placeholder="MSSV" required>
                            <label for="username" class="required">MSSV / Username <small>(bắt buộc)</small></label>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-text hint mt-1">Ví dụ: 22CT123</div>
                    </div>

                    {{-- Họ tên --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="full_name" id="full_name"
                                value="{{ old('full_name') }}"
                                class="form-control @error('full_name') is-invalid @enderror"
                                placeholder="Họ tên" required>
                            <label for="full_name" class="required">Họ và tên</label>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="col-12 pt-2">
                        <div class="section-title">Thông tin cá nhân</div>
                    </div>

                    {{-- Ngày sinh --}}
                    {{-- <div class="col-md-4">
                        <label for="dob" class="form-label mb-1">Ngày sinh</label>
                        <input type="date" name="dob" id="dob"
                            value="{{ old('dob') }}"
                            class="form-control @error('dob') is-invalid @enderror">
                        @error('dob')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div> --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" name="dob" id="dob"
                                value="{{ old('dob') }}"
                                class="form-control @error('dob') is-invalid @enderror"
                                placeholder="Ngày sinh">
                            <label for="dob">Ngày sinh</label>
                            @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Giới tính --}}
                    {{-- <div class="col-md-4">
                        <label for="gender" class="form-label mb-1">Giới tính</label>
                        <select name="gender" id="gender"
                            class="form-select @error('gender') is-invalid @enderror">
                            <option value="">-- Chọn --</option>
                            @foreach (['Nam','Nữ','Khác'] as $g)
                            <option value="{{ $g }}" {{ old('gender')===$g ? 'selected':'' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('gender')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div> --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select name="gender" id="gender"
                                class="form-select @error('gender') is-invalid @enderror">
                                <option value="">-- Chọn --</option>
                                @foreach (['Nam','Nữ','Khác'] as $g)
                                <option value="{{ $g }}" {{ old('gender')===$g ? 'selected':'' }}>{{ $g }}</option>
                                @endforeach
                            </select>
                            <label for="gender" class="form-label mb-1">Giới tính</label>
                            @error('gender')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- SĐT --}}
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror"
                                placeholder="Số điện thoại">
                            <label for="phone">Số điện thoại</label>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="email" name="email" id="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Email">
                            <label for="email">Email</label>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Lớp --}}
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" name="class" id="class"
                                value="{{ old('class') }}"
                                class="form-control @error('class') is-invalid @enderror"
                                placeholder="Lớp">
                            <label for="class">Lớp</label>
                            @error('class')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Khoa --}}
                    {{-- Khoa --}}
                    <div class="col-12">
                        <div class="form-floating">
                            <select name="faculty" id="faculty"
                                class="form-select @error('faculty') is-invalid @enderror" required>
                                <option value="">-- Chọn khoa --</option>
                                @foreach ([
                                'Công nghệ thông tin & Kinh tế số',
                                'Kế toán',
                                'Ngân hàng',
                                'Tài chính',
                                'Chất lượng cao',
                                'Khác',
                                'Tất cả'
                                ] as $facultyOption)
                                <option value="{{ $facultyOption }}"
                                    {{ old('faculty') === $facultyOption ? 'selected' : '' }}>
                                    {{ $facultyOption }}
                                </option>
                                @endforeach
                            </select>
                            <label for="faculty" class="form-label mb-1 required">Khoa <span class="text-danger">*</span></label>
                            @error('faculty')
                            <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    {{-- Ghi chú mật khẩu mặc định --}}
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-start gap-2 mb-0">
                            <div>
                                <strong>Mật khẩu mặc định:</strong> <code>12345678</code>.
                                Vui lòng yêu cầu sinh viên đổi mật khẩu sau lần đăng nhập đầu tiên.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Bar --}}
            <div class="sticky-actions d-flex justify-content-end gap-2">
                <a href="{{ route('students.index') }}" class="btn btn-soft rounded-pill px-3">Huỷ</a>
                <button class="btn btn-primary rounded-pill px-4" type="submit">Lưu</button>
            </div>
        </div>
    </form>
</div>

@endsection