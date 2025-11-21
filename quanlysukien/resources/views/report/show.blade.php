@extends('layouts.app')
@section('title', 'Báo cáo sự kiện')

@push('styles')
<style>
    body {
        background: #f8fafc;
    }

    /* 🌈 Header */
    .page-header {
        border-radius: 16px;
        background: linear-gradient(135deg, #6366f1, #06b6d4);
        color: #fff;
        padding: 28px 32px;
        margin-bottom: 28px;
        box-shadow: 0 3px 15px rgba(99, 102, 241, 0.25);
    }

    .page-header h3 {
        font-weight: 700;
        font-size: 1.5rem;
    }

    .page-header i {
        color: #fff;
        opacity: 0.95;
    }

    /* 🧭 Bộ lọc */
    .filter-card {
        background: #fff;
        border-radius: 14px;
        padding: 24px 28px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        margin-bottom: 28px;
        border: 1px solid #eef2f7;
    }

    .form-select {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        transition: 0.2s;
    }

    .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.1rem rgba(99, 102, 241, 0.2);
    }

    /* 📊 Card & bảng */
    .card-modern {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #eef2f7;
    }

    .card-modern h5 {
        font-weight: 600;
        color: #1e293b;
    }

    /* 🟩 Bảng */
    .table {
        border-radius: 12px;
        overflow: hidden;
    }

    thead th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #334155;
        font-size: 0.95rem;
    }

    tbody td {
        vertical-align: middle;
    }

    .badge {
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 6px 10px;
    }

    .alert-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e3a8a;
        font-weight: 500;
        border-radius: 10px;
    }

    .btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
    }

    .btn-success:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="container py-4" style="max-width:1100px;">

    {{-- 🌈 Header --}}
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3><i class="bi bi-bar-chart-line me-2"></i>Báo cáo sự kiện: {{ $event->event_name }}</h3>
            <div class="small opacity-75">📅 Thống kê chi tiết sinh viên tham gia sự kiện</div>
        </div>
        <a href="{{ route('reports.export', [
            'eventId' => $event->event_id,
            'faculty' => request('faculty'),
            'class' => request('class'),
            'status' => request('status')
        ]) }}" class="btn btn-success rounded-pill px-4">
            <i class="bi bi-file-earmark-excel me-1"></i> Xuất Excel
        </a>
    </div>

    {{-- 🧭 Bộ lọc --}}
    <div class="filter-card">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="faculty" class="form-label fw-semibold">Khoa</label>
                <select name="faculty" id="faculty" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả khoa --</option>
                    @foreach($faculties as $f)
                        <option value="{{ $f }}" {{ $selectedFaculty == $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="class" class="form-label fw-semibold">Lớp</label>
                <select name="class" id="class" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả lớp --</option>
                    @foreach($classes as $c)
                        <option value="{{ $c }}" {{ $selectedClass == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="status" class="form-label fw-semibold">Trạng thái điểm danh</label>
                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Tất cả --</option>
                    <option value="attended" {{ $selectedStatus == 'attended' ? 'selected' : '' }}>Đã điểm danh</option>
                    <option value="not" {{ $selectedStatus == 'not' ? 'selected' : '' }}>Chưa điểm danh</option>
                </select>
            </div>
        </form>
    </div>

    {{-- 📊 Tóm tắt nhanh --}}
    <div class="alert alert-info text-center py-2 mb-4">
        <strong>Tổng:</strong> {{ $totalStudents }} sinh viên |
        <span class="text-success fw-semibold">Đã điểm danh: {{ $attendedCount }}</span> |
        <span class="text-secondary fw-semibold">Chưa điểm danh: {{ $notAttendedCount }}</span>
    </div>

    {{-- 📈 Biểu đồ --}}
    <div class="card-modern text-center p-4 mb-4">
        <h5 class="mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Tỉ lệ điểm danh</h5>
        <div style="position: relative; height:360px; width:360px; margin:0 auto;">
            <canvas id="attendanceChart"></canvas>
        </div>
    </div>

    {{-- 📋 Bảng chi tiết --}}
    <div class="card-modern p-4">
        <h5 class="fw-semibold mb-3 text-center">
            <i class="bi bi-people-fill text-primary me-2"></i>Danh sách sinh viên tham gia
        </h5>
        <div class="table-responsive">
            <table class="table align-middle text-center">
                <thead>
                    <tr>
                        <th>Mã SV</th>
                        <th>Họ tên</th>
                        <th>Lớp</th>
                        <th>Khoa</th>
                        <th>Trạng thái</th>
                        <th>Ảnh điểm danh</th>
                        <th>Thời gian check-in</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($studentStats as $st)
                        <tr>
                            <td>{{ $st->username }}</td>
                            <td>{{ $st->full_name }}</td>
                            <td>{{ $st->class }}</td>
                            <td>{{ $st->faculty }}</td>
                            <td>
                                <span class="badge {{ $st->status == 'Đã điểm danh' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $st->status }}
                                </span>
                            </td>
                            <td>
                                @if($st->image_url)
                                    <a href="{{ $st->image_url }}" target="_blank">
                                        <img src="{{ $st->image_url }}" alt="Ảnh điểm danh"
                                            style="width:70px;height:70px;object-fit:cover;border-radius:8px;">
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $st->checkin_time ? date('H:i:s d/m/Y', strtotime($st->checkin_time)) : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ChartJS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('attendanceChart'), {
    type: 'doughnut',
    data: {
        labels: @json($labels),
        datasets: [{
            data: @json($counts),
            backgroundColor: ['#3b82f6', '#cbd5e1'],
            borderWidth: 0,
            cutout: '72%',
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endsection
