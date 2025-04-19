@extends('Layout.App')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách Ticket</h2>

    <!-- Thông báo lớn -->
    <div class="card p-4 mb-4 shadow">
        <div class="text-center">
            <h2 class="text-danger font-weight-bold">⚠ LƯU Ý ⚠</h2>
            <p><strong>1. Chỉ sử dụng tickets khi thực sự cần thiết.</strong></p>
            <p><strong>2. Đối với những dịch vụ LiveStream như Live Tiktok hoặc Live Facebook nên chụp ảnh lại ngày giờ trước khi lên View để được hoàn tiền nếu có lỗi.</strong></p>
            <p><strong>3. Những dịch vụ không hoàn được tiền, khi sử dụng ticket chắc chắn sẽ được hoàn tiền, trừ những dịch vụ lên quá nhanh.</strong></p>
            <p><strong>4. Hãy đọc mục <a href="/support-document" class="text-primary font-weight-bold">Hướng dẫn</a> trước để không mất tiền oan.</strong></p>
            <p><strong>Các vấn đề khác xin liên hệ Telegram <a href="https://t.me/MinkaSolana" class="text-primary font-weight-bold">@MinkaSolana</a></strong></p>
        </div>
    </div>

    <!-- Ô tìm kiếm Order ID -->
    <div class="mb-3">
        <label for="searchOrderId" class="form-label">Tìm kiếm theo Order ID:</label>
        <input type="text" id="searchOrderId" class="form-control" placeholder="Nhập Order ID..." onkeyup="searchOrder()">
    </div>

    <!-- Nút tạo vé -->
    <a href="{{ route('ticket.create') }}" class="btn btn-success mb-3">Tạo vé hỗ trợ mới</a>

    <!-- Bảng hiển thị Ticket -->
    <table class="table table-bordered table-hover" id="ticketTable">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Order ID</th> <!-- Cột mới -->
                <th>Trạng thái</th>
                <th>Ngày gửi</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusMap = [
                    'pending' => ['text' => 'Chờ xử lý', 'class' => 'badge-warning'],
                    'in_progress' => ['text' => 'Cần thêm thông tin', 'class' => 'badge-primary'],
                    'completed' => ['text' => 'Hoàn thành', 'class' => 'badge-success'],
                    'cancelled' => ['text' => 'Hủy', 'class' => 'badge-danger']
                ];
            @endphp

            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ticket->title }}</td>
                <td class="order-id">{{ $ticket->order_id ?? 'N/A' }}</td> <!-- Hiển thị Order ID -->
                <td>
                    @php 
                        $status = $statusMap[$ticket->status] ?? ['text' => $ticket->status, 'class' => 'badge-secondary'];
                    @endphp
                    <span class="badge {{ $status['class'] }} text-dark">{{ $status['text'] }}</span>
                </td>
                <td>{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                <td><a href="{{ route('ticket.view', $ticket->id) }}" class="btn btn-info">Xem</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- JavaScript tìm kiếm Order ID -->
<script>
    function searchOrder() {
        let input = document.getElementById("searchOrderId").value.toLowerCase();
        let table = document.getElementById("ticketTable");
        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            let orderIdCell = rows[i].getElementsByClassName("order-id")[0];

            if (orderIdCell) {
                let orderId = orderIdCell.textContent || orderIdCell.innerText;

                if (orderId.toLowerCase().includes(input)) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    }
</script>
@endsection
