@extends('Layout.App')
@section('title', 'Lịch sử tài khoản')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Nhật kí hoạt động</h4>
                    <div class="mb-3">
                        <div class="table-responsive">
                            <div id="" class="dataTables_wrapper">
                                <table id="history"
                                    class="table border table-striped table-bordered display text-nowrap dataTable responsive w-100"
                                    aria-describedby="file_export_info">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tài khoản</th>
                                            <th>Loại</th>
                                            <th>Số tiền</th>
                                            <th>Số dư trước</th>
                                            <th>Số dư thay đổi</th>
                                            <th>Ghi chú</th>
                                            <th>Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
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
        createDataTable('#history', '{{ route('user.list.action', 'history-user') }}', [{
                data: 'id',
            },
            {
                data: 'username',
            },
            {
                data: 'action',
                render: function(data){
                    return `<span class="badge bg-primary">`+data+`</span>`
                }
            },
            {
                data: 'data',
                render: function(data){
                    return `<span class="text-danger">`+formatNumber(data)+`</span>`
                }
            },
            {
                data: 'old_data',
                render: function(data){
                    return `<span class="text-primary">`+formatNumber(data)+`</span>`
                }
            },
            {
                data: 'new_data',
                render: function(data){
                    return `<span class="text-success">`+formatNumber(data)+`</span>`
                }
            },
            {
                data: 'description',
            },
            {
                data: 'created_at',
            },
        ])
    </script>
@endsection
