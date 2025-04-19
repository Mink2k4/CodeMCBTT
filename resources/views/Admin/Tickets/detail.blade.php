@extends('Admin.Layout.App')

@section('content')
    <div class="container">
        <h2>Chi tiết Ticket</h2>
        <p><strong>ID:</strong> {{ $ticket->id }}</p>
        <p><strong>Người dùng:</strong> {{ $ticket->user->name ?? 'N/A' }}</p>
        <p><strong>Tiêu đề:</strong> {{ $ticket->title }}</p>
        <p><strong>Trạng thái:</strong> {{ $ticket->status }}</p>
        <p><strong>Order ID:</strong> {{ $ticket->order_id ?? 'N/A' }}</p>
        <p><strong>Nội dung:</strong> {{ $ticket->description }}</p>
        <p><strong>Ngày tạo:</strong> {{ $ticket->created_at }}</p>
        @if($ticket->image)
            <div class="mt-3">
                <p><strong>Hình ảnh:</strong></p>
                <img src="{{ asset('storage/' . $ticket->image) }}" 
                     alt="Ticket Image" 
                     class="img-fluid rounded shadow" 
                     style="max-width: 400px;">
            </div>
        @endif
        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-primary">Chỉnh Sửa</a>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
@endsection
