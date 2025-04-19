@extends('Admin.Layout.App')

@section('content')
    <div class="container">
        <h2>Danh sách Tickets</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tiêu đề</th>
                    <th>Trạng thái</th>
                    <th>Ngày gửi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>
                            <select class="form-select status-select" data-ticket-id="{{ $ticket->id }}">
                                <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>Cần thêm thông tin</option>
                                <option value="completed" {{ $ticket->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $ticket->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </td>
                        <td>{{ $ticket->created_at }}</td>
                        <td>
                            <a href="{{ route('admin.tickets.view', $ticket->id) }}" class="btn btn-primary">Xem</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".status-select").forEach(select => {
                select.addEventListener("change", function () {
                    let ticketId = this.getAttribute("data-ticket-id");
                    let newStatus = this.value;

                    fetch(`/admin/tickets/update-status/${ticketId}`, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Cập nhật trạng thái thành công!");
                        } else {
                            alert("Có lỗi xảy ra khi cập nhật trạng thái!");
                        }
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                    });
                });
            });
        });
    </script>
@endsection
