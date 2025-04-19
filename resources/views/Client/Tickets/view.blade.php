@extends('Layout.App')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-dark">Chi tiết Ticket</h2>

    @php
        $orderID = $ticket->order_id ?? 'N/A';
        $description = $ticket->description ?? 'Không có nội dung';
        $statusMap = [
            'pending' => 'Chờ xử lý',
            'in_progress' => 'Cần thêm thông tin',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy'
        ];
        $statusText = $statusMap[$ticket->status] ?? 'Chưa xác định';
        $createdAt = \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i');
    @endphp

    <p class="fs-4"><strong>Order ID:</strong> <span class="fw-bold">{{ $orderID }}</span></p>
    <p class="fs-4"><strong>Tiêu đề:</strong> <span class="fw-bold">{{ $ticket->title }}</span></p>
    <p class="fs-4"><strong>Trạng thái:</strong> 
        <span class="fw-bold text-{{ $ticket->status == 'completed' ? 'success' : ($ticket->status == 'cancelled' ? 'danger' : 'warning') }}">
            {{ $statusText }}
        </span>
    </p>
    <p class="fs-4"><strong>Ngày gửi:</strong> <span class="fw-bold">{{ $createdAt }}</span></p>

    <!-- Nếu trạng thái là 'Cần thêm thông tin', hiển thị form sửa -->
    @if($ticket->status == 'in_progress')
        <form action="{{ route('ticket.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="description" class="form-label">Thêm lý do / Ảnh</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Tải lên ảnh mới (nếu cần):</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
    @else
        <p class="fs-4"><strong>Nội dung:</strong> <span class="fw-bold">{{ nl2br(e($description)) }}</span></p>
    @endif

    <!-- Hiển thị ảnh nếu có -->
    @if($ticket->image)
        <div class="mt-3">
            <p class="fs-4"><strong>Hình ảnh:</strong></p>
            <img src="{{ asset('storage/ticket_images/' . basename($ticket->image)) }}" alt="Ticket Image" class="img-fluid rounded shadow" style="max-width: 400px;">
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('tickets') }}" class="btn btn-secondary btn-lg">Quay lại</a>
    </div>
</div>
@endsection