@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')
@include('template.baner')

{{-- ========== Sự kiện đang diễn ra ========== --}}
<div class="px-5 pt-4">
    {{-- 🔍 Form tìm kiếm sự kiện --}}
    <div class="px-5 pt-4 text-center">
        <form method="GET" action="{{ route('home') }}" class="search-form mx-auto">
            <div class="input-group input-group-lg shadow-sm" style="max-width: 600px; border-radius: 50px; overflow: hidden;">
                <span class="input-group-text bg-white border-0 ps-4">
                    <i class="bi bi-search text-secondary"></i>
                </span>
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    class="form-control border-0 fs-6"
                    placeholder="Nhập tên sự kiện bạn muốn tìm..."
                    style="box-shadow: none;">
                <button class="btn btn-primary px-4" type="submit" style="border-radius: 0 50px 50px 0;">
                    Tìm kiếm
                </button>
            </div>

            @if(request('q'))
            <div class="mt-2 small text-muted">
                Kết quả cho: <strong>{{ request('q') }}</strong>
                <a href="{{ route('home') }}" class="text-decoration-none ms-2">✖ Xóa tìm kiếm</a>
            </div>
            @endif
        </form>
    </div>

    <h3 class="fw-bold mb-4">Sự kiện đang diễn ra</h3>

    @if($ongoing->isEmpty())
    <div class="alert alert-secondary">Hiện chưa có sự kiện nào đang diễn ra.</div>
    @else
    <div class="row g-4">
        @foreach ($ongoing as $e)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">
                <img
                    src="{{ $e->image_url ?: 'https://picsum.photos/seed/ongoing' . $e->event_id . '/400/220' }}"
                    class="card-img-top"
                    alt="Ảnh sự kiện {{ $e->event_name }}"
                    style="object-fit: cover; height: 220px;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-semibold mb-2">{{ $e->event_name }}</h5>
                    <p class="text-muted mb-2">
                        {{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d/m/Y H:i') }}
                        –
                        {{ \Illuminate\Support\Carbon::parse($e->end_time)->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-geo-alt"></i> {{ $e->location ?? '—' }}
                    </p>
                    <a href="{{ route('events.show', $e->event_id) }}" class="btn btn-primary rounded-pill mt-auto">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ========== Tin tức & Sự kiện nổi bật (10 mới nhất) ========== --}}
<div class="px-5 pt-5 pb-4">
    <h3 class="fw-bold mb-4">Tin tức & Sự kiện nổi bật</h3>

    @if($featured->isEmpty())
    <div class="alert alert-secondary">Chưa có sự kiện nào.</div>
    @else
    <div class="row g-4">
        @foreach ($featured as $e)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0">

                <img
                    src="{{ $e->image_url ?: 'https://picsum.photos/seed/ongoing' . $e->event_id . '/400/220' }}"
                    class="card-img-top"
                    alt="Ảnh sự kiện {{ $e->event_name }}"
                    style="object-fit: cover; height: 220px;">


                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-semibold mb-2">{{ $e->event_name }}</h5>
                    <p class="text-muted small mb-2">
                        Bắt đầu: {{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-geo-alt"></i> {{ $e->location ?? '—' }}
                    </p>
                    <a href="{{ route('events.show', $e->event_id) }}" class="btn btn-outline-primary rounded-pill mt-auto">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>


@endsection