@extends('layouts.app')
@section('title', 'Đánh giá sự kiện')

@section('content')
    <div class="container mt-4">
        <h3 class="fw-bold text-primary mb-4">
            <i class="bi bi-calendar-event me-2"></i>Danh sách sự kiện
        </h3>


        <form method="GET"class="d-flex" style="max-width:400px;">
            <input type="text" name="q" value="{{ $q }}" class="form-control me-2"
                placeholder="Tìm kiếm theo tên hoặc mã sự kiện">
            <button class="btn btn-outline-secondary">Tìm</button>
        </form>

        <table class="table table-striped table-bordered align-middle mt-3">
            <thead class="table-light">
                <tr>
                    <th>Mã sự kiện</th>
                    <th>Tên sự kiện</th>
                    <th>Người phụ trách</th>
                    <th>Thời gian bắt đầu</th>
                    <th>Thời gian kết thúc</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->event_code ?? '-' }}</td>
                        <td>{{ $event->event_name }}</td>
                        <td>{{ $event->manager_name ?? 'Chưa gán' }}</td>
                        <td>{{ $event->start_time }}</td>
                        <td>{{ $event->end_time }}</td>
                        <td>
                            @if ($event->status === 'Đã đánh giá')
                                <a href="{{ route('evaluations.index', ['event_id' => $event->event_id]) }}"
                                    class="btn btn-primary btn-sm">
                                    ✅ Đã đánh giá
                                </a>
                            @else
                                <a href="{{ route('evaluations.index', ['event_id' => $event->event_id]) }}"
                                    class="btn btn-primary btn-sm">
                                    Đánh giá
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
