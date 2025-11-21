@extends('layouts.app')
@section('title', 'Chi tiết sinh viên')

@section('content')
<div class="container py-4" style="max-width:900px;">
    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary mb-3">← Quay lại</a>

    {{-- 🔹 Thông tin sinh viên --}}
    <div class="card shadow-sm p-4 mb-4">
        <h4 class="fw-bold text-primary mb-3">Thông tin sinh viên</h4>
        <div class="row g-3">
            <div class="col-md-6"><strong>Họ tên:</strong> {{ $user->full_name }}</div>
            <div class="col-md-6"><strong>MSSV:</strong> {{ $user->username }}</div>
            <div class="col-md-6"><strong>Lớp:</strong> {{ $user->class ?? '—' }}</div>
            <div class="col-md-6"><strong>Khoa:</strong> {{ $user->faculty ?? '—' }}</div>
            <div class="col-md-6"><strong>Giới tính:</strong> {{ $user->gender ?? '—' }}</div>
            <div class="col-md-6"><strong>Email:</strong> {{ $user->email ?? '—' }}</div>
            <div class="col-md-6"><strong>Điện thoại:</strong> {{ $user->phone ?? '—' }}</div>
        </div>
    </div>

    {{-- 🔹 Danh sách sự kiện --}}
    <div class="card shadow-sm p-4">
        <h4 class="fw-bold text-primary mb-3">📋 Sự kiện đã điểm danh</h4>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Tên sự kiện</th>
                    <th>Thời gian diễn ra</th>
                    <th>Check-in</th>
                    <th>Trạng thái</th>
                    <th>Ảnh điểm danh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $ev)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $ev->event_name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($ev->start_time)->format('d/m/Y H:i') }} - 
                            {{ \Carbon\Carbon::parse($ev->end_time)->format('H:i') }}
                        </td>
                        <td>{{ $ev->checkin_time ? \Carbon\Carbon::parse($ev->checkin_time)->format('d/m/Y H:i') : '—' }}</td>
                        <td>
                            <span class="badge {{ $ev->status === 'Có mặt' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $ev->status }}
                            </span>
                        </td>
                        <td>
                            @if($ev->image_url)
                                <a href="{{ $ev->image_url }}" target="_blank">
                                    <img src="{{ $ev->image_url }}" alt="Ảnh điểm danh" width="50" height="50" class="rounded border">
                                </a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">Chưa tham gia sự kiện nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
