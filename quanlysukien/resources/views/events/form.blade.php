{{-- resources/views/events/form.blade.php --}}
@extends('layouts.app')
@section('title', $mode === 'create' ? 'Tạo sự kiện' : 'Sửa sự kiện')

@push('styles')
<style>
    body {
        background: #f8fafc;
    }

    /* 🟦 Khối chính của form */
    .form-wrapper {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        padding: 40px 50px;
        margin: 0 auto;
        max-width: 900px;
        border: 1px solid #eef2f7;
    }

    .page-header {
        border-radius: 14px;
        background: linear-gradient(135deg, #6366f1, #06b6d4);
        color: #fff;
        padding: 22px 28px;
        margin-bottom: 32px;
        box-shadow: 0 3px 15px rgba(99, 102, 241, 0.25);
    }

    .page-header h4 {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 4px;
    }

    /* 🪄 Tiêu đề section */
    .section-title {
        font-weight: 600;
        font-size: 1.05rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .section-title::before {
        content: "";
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #6366f1;
        border-radius: 50%;
    }

    /* ✏️ Input & select */
    .form-control,
    .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        transition: 0.25s;
        font-size: 0.95rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.15rem rgba(99, 102, 241, 0.25);
    }

    /* 🧷 Footer nút */
    .sticky-actions {
        border-top: 1px solid #f1f5f9;
        background: #fafafa;
        padding: 16px 24px;
        border-bottom-left-radius: 14px;
        border-bottom-right-radius: 14px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-soft {
        background: #f1f5f9;
        color: #334155;
        border-radius: 50px;
        padding: 8px 22px;
    }

    .btn-soft:hover {
        background: #e2e8f0;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #06b6d4);
        border: none;
        border-radius: 50px;
        padding: 8px 28px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
        transition: 0.25s;
    }

    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    label.form-label {
        font-weight: 500;
        color: #475569;
    }
</style>
@endpush



@section('content')
<div class="container py-4" style="max-width:900px">
    {{-- 🧭 Header --}}
    <div class="page-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="small opacity-75">📅 Quản lý sự kiện</div>
                <h4 class="mb-1">{{ $mode === 'create' ? 'Tạo sự kiện mới' : 'Chỉnh sửa sự kiện' }}</h4>
                <div class="small">
                    <a href="{{ route('events.index') }}" class="text-white text-decoration-underline">Danh sách</a>
                    <span class="mx-1">/</span>
                    <span class="opacity-75">{{ $mode === 'create' ? 'Tạo mới' : 'Cập nhật' }}</span>
                </div>
            </div>
            <a href="{{ route('events.index') }}" class="btn btn-light btn-sm rounded-pill px-3">← Quay lại</a>
        </div>
    </div>

    {{-- ⚠️ Lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-0 rounded-3">
            <strong>⚠️ Có lỗi xảy ra:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 📝 Form --}}
        <div class="form-wrapper">
    <form method="POST" enctype="multipart/form-data"
          action="{{ $mode === 'create' ? route('events.store') : route('events.update', $event->event_id) }}">
        @csrf
        @if ($mode === 'edit') @method('PUT') @endif

        <div class="card-modern">
            <div class="card-body p-4">
                {{-- Thông tin chung --}}
                <div class="section-title">🧾 Thông tin chung</div>
                <div class="row g-4 mt-1">
                    <div class="col-md-8">
                        <label class="form-label required">Tên sự kiện</label>
                        <input name="event_name" class="form-control" required
                            value="{{ old('event_name',$event->event_name) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Đơn vị tổ chức</label>
                        <input name="organizer" class="form-control"
                            value="{{ old('organizer',$event->organizer) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Người quản lý</label>
                        <select name="manager_id" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach ($managers as $m)
                                <option value="{{ $m->user_id }}" @selected(old('manager_id',$event->manager_id)==$m->user_id)>
                                    {{ $m->full_name ?? $m->username }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Học kỳ / Năm học --}}
                    <div class="col-md-4">
                        <label class="form-label required">Học kỳ</label>
                        <select name="semester" class="form-select" required>
                            <option value="">-- Chọn học kỳ --</option>
                            @foreach (['HKI'=>'Học kỳ I','HKII'=>'Học kỳ II','HKHE'=>'Học kỳ Hè'] as $val=>$label)
                                <option value="{{ $val }}" @selected(old('semester',$event->semester)===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Năm học</label>
                        <input name="academic_year" class="form-control" required
                            value="{{ old('academic_year',$event->academic_year) }}">
                    </div>
                </div>

                {{-- Thời gian --}}
                <hr class="my-4">
                <div class="section-title">⏰ Thời gian</div>
                <div class="row g-4 mt-1">
                    <div class="col-md-6">
                        <label class="form-label required">Bắt đầu</label>
                        <input type="datetime-local" name="start_time" class="form-control"
                            value="{{ old('start_time',$event->start_time_input) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label required">Kết thúc</label>
                        <input type="datetime-local" name="end_time" class="form-control"
                            value="{{ old('end_time',$event->end_time_input) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hạn đăng ký</label>
                        <input type="datetime-local" name="registration_deadline"
                            class="form-control"
                            value="{{ old('registration_deadline', $event->registration_deadline ? \Carbon\Carbon::parse($event->registration_deadline)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>

                {{-- Chi tiết --}}
                <hr class="my-4">
                <div class="section-title">📍 Chi tiết sự kiện</div>
                <div class="row g-4 mt-1">
                    <div class="col-md-12">
                        <label class="form-label">Địa điểm</label>
                        <input name="location" class="form-control" value="{{ old('location',$event->location) }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description',$event->description) }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Ảnh minh họa</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if ($event->image_url)
                            <img src="{{ $event->image_url }}" class="preview-image mt-2" alt="Preview">
                        @endif
                    </div>
                </div>

                {{-- Đối tượng --}}
                <hr class="my-4">
                <div class="section-title">🎯 Đối tượng áp dụng</div>
                <div class="row g-4 mt-1">
                    <div class="col-md-4">
                        <label class="form-label">Khoa</label>
                        <select name="target_faculty" class="form-select">
                            <option value="">-- Chọn khoa --</option>
                            @foreach ($faculties as $f)
                                <option value="{{ $f }}" @selected(old('target_faculty',$event->target_faculty)===$f)>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Lớp</label>
                        <select name="target_class" class="form-select">
                            <option value="">-- Chọn lớp --</option>
                            @foreach ($classes as $cls)
                                <option value="{{ $cls }}" @selected(old('target_class',$event->target_class)===$cls)>{{ $cls }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Giới tính</label>
                        <select name="target_gender" class="form-select">
                            @foreach(['Tất cả','Nam','Nữ'] as $g)
                                <option value="{{ $g }}" @selected(old('target_gender',$event->target_gender)===$g)>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label required">Số lượng tối đa</label>
                        <input type="number" min="1" name="max_participants" class="form-control"
                            value="{{ old('max_participants',$event->max_participants) }}" required>
                    </div>
                </div>
            </div>

            {{-- Footer nút --}}
            <div class="sticky-actions">
                <a href="{{ route('events.index') }}" class="btn btn-soft rounded-pill px-3">Huỷ</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    {{ $mode === 'create' ? '✨ Tạo sự kiện' : '💾 Cập nhật' }}
                </button>
            </div>
        </div>
    </form>
        </div>
</div>
@endsection
