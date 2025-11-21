@extends('layouts.app')
@section('title', 'Báo cáo')

@section('content')
<style>
    #attendanceChart {
        max-width: 420px;
        max-height: 420px;
        margin: 0 auto;
    }

    .header {
        padding-top: 20px;
        padding-left: 180px;
        padding-right: 180px;
    }

    .chart-card,
    .table-card {
        max-width: 850px;
        margin: 0 auto 30px auto;
        border-radius: 16px;
    }

    .table {
        font-size: 15px;
        border-radius: 8px;
        overflow: hidden;
    }

    .table th {
        background-color: #f3f4f6;
        font-weight: 600;
        color: #374151;
    }

    .table tbody tr:hover {
        background-color: #f9fafb;
        transition: 0.2s;
    }

    .badge {
        font-size: 0.85rem;
        padding: 6px 10px;
        border-radius: 12px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 header">
    <h3 class="fw-bold text-primary">
        <i class="bi bi-bar-chart-line me-2"></i>Báo cáo điểm danh
    </h3>
    <a href="{{ route('reports.export') }}" class="btn btn-success rounded-pill">
        <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
    </a>

</div>

{{-- Biểu đồ --}}
<div class="card p-4 shadow-sm text-center chart-card">
    <h5 class="fw-semibold mb-3">Tỉ lệ điểm danh toàn trường</h5>
    <div style="position: relative; height:380px; width:380px; margin:0 auto;">
        <canvas id="attendanceChart"></canvas>
    </div>
</div>

{{-- Bảng chi tiết --}}
<div class="card p-4 shadow-sm table-card">
    <h5 class="fw-semibold mb-3 text-center">Chi tiết theo sự kiện</h5>
    <table class="table align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>Mã sự kiện</th>
                <th>Tên sự kiện</th>
                <th>Tổng SV (Max)</th>
                <th>Đã điểm danh</th>
                <th>Tỷ lệ (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eventStats as $event)
            <tr>
                <td>{{ $event->event_code ?? '-' }}</td>
                <td>{{ $event->event_name }}</td>
                <td>{{ $event->max_participants ?? 0 }}</td>
                <td>{{ $event->attended_students }}</td>
                <td>
                    <span class="badge 
                            {{ $event->attendance_rate >= 90 ? 'bg-success' : 
                               ($event->attendance_rate >= 70 ? 'bg-primary' : 'bg-danger') }}">
                        {{ $event->attendance_rate }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($labels),
            datasets: [{
                data: @json($counts),
                backgroundColor: ['#3b82f6', '#d1d5db', '#fbbf24', '#ef4444'],
                borderWidth: 0,
                cutout: '70%',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 16,
                        font: {
                            size: 13
                        }
                    }
                }
            }
        }
    });
</script>



@endsection