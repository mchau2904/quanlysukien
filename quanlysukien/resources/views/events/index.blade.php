@extends('layouts.app')
@section('title', 'Sự kiện')

@section('content')

{{-- ADMIN VIEW --}}
@if(auth()->user()?->role === 'admin')
<div class="container px-4 pt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold text-primary m-0"><i class="bi bi-calendar-event me-2"></i>Quản lý Sự kiện</h4>
        <a href="{{ route('events.create') }}" class="btn btn-gradient rounded-pill">
            <i class="bi bi-plus-lg me-1"></i> Tạo Sự kiện
        </a>
    </div>

    {{-- Bộ lọc --}}
    <form class="row g-2 align-items-end mb-3" method="get" action="{{ route('events.index') }}">
        <div class="col-md-3">
            <label class="form-label">Tìm kiếm tên/mã/địa điểm</label>
            <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Nhập từ khoá...">
        </div>
        <div class="col-md-3">
            <label class="form-label">Đơn vị tổ chức</label>
            <select name="org" class="form-select">
                <option value="">Tất cả đơn vị</option>
                @foreach($organizers as $o)
                <option value="{{ $o }}" @selected(request('org')===$o)>{{ $o }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Trạng thái</label>
            <select name="status" class="form-select">
                <option value="">Tất cả</option>
                <option value="ongoing" @selected(request('status')==='ongoing' )>Đang diễn ra</option>
                <option value="upcoming" @selected(request('status')==='upcoming' )>Sắp diễn ra</option>
                <option value="past" @selected(request('status')==='past' )>Đã kết thúc</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Sắp xếp</label>
            <select name="sort" class="form-select">
                <option value="time_desc" @selected(request('sort','time_desc')==='time_desc' )>Theo thời gian ↓</option>
                <option value="time_asc" @selected(request('sort')==='time_asc' )>Theo thời gian ↑</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button class="btn btn-outline-primary flex-fill"><i class="bi bi-search"></i> Lọc</button>
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary flex-fill">Đặt lại</a>
        </div>
    </form>

    {{-- Bảng --}}
    <div class="card p-3 shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã</th>
                        <th>Tên Sự kiện</th>
                        <th>Đơn vị</th>
                        <th>Người phụ trách</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian kết thúc</th>
                        <th>Địa điểm</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adminList ?? [] as $e)
                    @php
                    $now = now();
                    $status = $e->end_time < $now ? 'past' : ($e->start_time > $now ? 'upcoming' : 'ongoing');
                        @endphp
                        <tr>
                            <td>{{ $e->event_code ?? $e->event_id }}</td>
                            <td class="fw-semibold">{{ $e->event_name }}</td>
                            <td>{{ $e->organizer ?? '—' }}</td>
                            <td>{{ $e->manager?->full_name ?? '—' }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($e->start_time)->format('Y-m-d H:i') }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($e->end_time)->format('Y-m-d H:i') }}</td>
                            <td>{{ $e->location ?? '—' }}</td>
                            <td>
                                @if($status==='ongoing')
                                <span class="badge bg-success-subtle text-success">Đang diễn ra</span>
                                @elseif($status==='upcoming')
                                <span class="badge bg-primary-subtle text-primary">Sắp diễn ra</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary">Đã kết thúc</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">

                                    {{-- Nút xem --}}
                                    <a href="{{ route('events.show', $e->event_id) }}"
                                        class="btn btn-sm btn-outline-info" title="Xem">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    {{-- Nút sửa (chỉ hiện khi chưa kết thúc) --}}
                                    @if($status !== 'past')
                                    <a href="{{ route('events.edit', $e->event_id) }}"
                                        class="btn btn-sm btn-outline-warning" title="Sửa">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    @endif

                                    {{-- Nút xoá --}}
                                    <form action="{{ route('events.destroy', $e->event_id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Xoá sự kiện này?')"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Xoá">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>

                                    {{-- Nút huy động / badge --}}
                                    @if($status === 'upcoming')
                                    @if(!$e->is_recruiting)
                                    <form action="{{ route('events.recruit', $e->event_id) }}" method="POST" class="w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"
                                            onclick="return confirm('📢 Gửi huy động tham gia sự kiện {{ $e->event_name }} đến sinh viên?')">
                                            Huy động
                                        </button>
                                    </form>
                                    @else
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        Đã huy động
                                    </span>
                                    @endif
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">Không có dữ liệu.</td>
                        </tr>
                        @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($adminList))
        <div class="mt-2">{{ $adminList->links() }}</div>
        @endif
    </div>
</div>

@else
{{-- STUDENT VIEW --}}
<div class="container d-flex justify-content-between align-items-center my-4 px-5">
    <h3 class="fw-bold text-primary"><i class="bi bi-calendar-event me-2"></i>Danh sách Sự kiện</h3>
</div>

<div class="card p-4 mb-5 px-5 container">
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-ongoing">Đang diễn ra</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-upcoming">Sắp diễn ra</button></li>
    </ul>

    <div class="tab-content">
        {{-- Đang diễn ra --}}
        <div class="tab-pane fade show active" id="tab-ongoing">
            @if($ongoing->isEmpty())
            <div class="alert alert-secondary mt-3">Hiện không có sự kiện nào đang diễn ra.</div>
            @else
            <div class="row g-4">
                @foreach ($ongoing as $e)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        {{-- ✅ Ảnh sự kiện --}}
                        <img
                            src="{{ $e->image_url ?: 'https://picsum.photos/seed/ongoing' . $e->event_id . '/400/220' }}"
                            class="card-img-top"
                            alt="Ảnh sự kiện {{ $e->event_name }}"
                            style="object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold mb-2 text-dark">{{ $e->event_name }}</h5>
                            <p class="text-muted mb-1">
                                {{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d/m/Y H:i') }}
                                –
                                {{ \Illuminate\Support\Carbon::parse($e->end_time)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-muted mb-3"><i class="bi bi-geo-alt"></i> {{ $e->location ?? '—' }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center gap-2">
                                <a href="{{ route('events.show', $e->event_id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    Chi tiết
                                </a>
                                <form action="{{ route('registrations.store', $e->event_id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-primary rounded-pill">Đăng ký tham gia</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Sắp diễn ra --}}
        <div class="tab-pane fade" id="tab-upcoming">
            @if($upcoming->isEmpty())
            <div class="alert alert-secondary mt-3">Chưa có sự kiện sắp diễn ra.</div>
            @else
            <div class="row g-4">
                @foreach ($upcoming as $e)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100">
                        {{-- ✅ Ảnh sự kiện --}}
                        <img
                            src="{{ $e->image_url ?: 'https://picsum.photos/seed/ongoing' . $e->event_id . '/400/220' }}"
                            class="card-img-top"
                            alt="Ảnh sự kiện {{ $e->event_name }}"
                            style="object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold mb-2 text-dark">{{ $e->event_name }}</h5>
                            <p class="text-muted mb-1">
                                Bắt đầu: {{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-muted mb-3"><i class="bi bi-geo-alt"></i> {{ $e->location ?? '—' }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center gap-2">
                                <a href="{{ route('events.show', $e->event_id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    Chi tiết
                                </a>
                                <form action="{{ route('registrations.store', $e->event_id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-primary rounded-pill">Đăng ký tham gia</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<style>
    .btn-gradient {
        background: linear-gradient(90deg, #3b82f6, #6366f1);
        color: #fff;
        border: none
    }

    .table>thead {
        vertical-align: middle;
    }

    .table>thead tr th{
        vertical-align: middle;
        text-align: center
    }

    .btn-gradient:hover {
        opacity: .9
    }

    .badge.bg-success-subtle {
        background: #e8f6ee
    }

    .badge.bg-primary-subtle {
        background: #e8f0ff
    }

    .badge.bg-secondary-subtle {
        background: #f1f2f4
    }
</style>

@endsection