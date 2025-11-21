@extends('layouts.app')
@section('title', 'Danh sách sự kiện')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold text-primary mb-4">
        <i class="bi bi-calendar-event me-2"></i>Danh sách sự kiện
    </h3>

    <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>Mã sự kiện</th>
                <th>Tên sự kiện</th>
                <th>Thời gian</th>
                <th>Địa điểm</th>
                <th>Báo cáo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr>
                <td>{{ $event->event_code ?? '-' }}</td>
                <td>{{ $event->event_name }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($event->start_time)) }}</td>
                <td>{{ $event->location ?? '-' }}</td>
                <td>
                    <a href="{{ route('reports.show', $event->event_id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-bar-chart-line"></i> Xem báo cáo
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-muted">Chưa có sự kiện nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection