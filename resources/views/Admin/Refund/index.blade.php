@extends('Admin.Layout.App')
@section('title', 'Danh sách yêu cầu hoàn tiền')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Danh sách yêu cầu hoàn tiền</h4>
                <div class="mb-3">
                    <div class="table-responsive">
                        <div id="" class="dataTables_wrapper">
                            <table id="refundTable" class="table border table-striped table-bordered display text-nowrap dataTable responsive w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Người yêu cầu</th>
                                        <th>Order ID</th>
                                        <th>Lý do</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày yêu cầu</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        console.log("API URL:", '{{ route('admin.list.refund') }}');
    
        $('#refundTable').DataTable({
            processing: true,
            serverSide: false, // Nếu API hỗ trợ server-side, đổi thành true
            ajax: '{{ route('admin.list.refund') }}',
            columns: [
                { data: 'id', title: "ID" },
                { data: 'user_name', title: "Người yêu cầu" }, // Cần API trả về `user_name`
                { data: 'order_id', title: "Order ID" },
                { data: 'reason', title: "Lý do" },
                { data: 'status', title: "Trạng thái", render: function(data, type, row) {
                    if(data === 'pending') return `<span class="badge badge-warning bg-warning">Chờ duyệt</span>`;
                    if(data === 'approved') return `<span class="badge badge-success bg-success">Đã duyệt</span>`;
                    if(data === 'archived') return `<span class="badge badge-success bg-success">Hoàn thành</span>`;
                    return `<span class="badge badge-danger bg-danger">Từ chối</span>`;
                }},
                { data: 'created_at', title: "Ngày yêu cầu", render: function(data) {
                    return new Date(data).toLocaleString(); // Format lại ngày tháng
                }},
                { data: 'id', title: "Thao tác", render: function(data) {
                    return `<a href="/admin/refund/view/${data}" class="btn btn-sm btn-primary">Xem</a>
                            <a href="javascript:;" onclick="deleteRefund('${data}')" class="btn btn-sm btn-danger">Xóa</a>`;
                }}
            ]
        });
    });
    function deleteRefund(id) {
        Swal.fire({
            title: 'Bạn có chắc chắn muốn xóa?',
            text: "Bạn sẽ không thể khôi phục lại dữ liệu!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/refund/delete/${id}`,
                    type: 'DELETE', // Sử dụng DELETE thay vì POST
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            Swal.fire('Đã xóa!', res.message, 'success');
                            $('#refundTable').DataTable().ajax.reload();
                        } else {
                            Swal.fire('Xóa thất bại!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Lỗi!', 'Không thể kết nối đến server.', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection