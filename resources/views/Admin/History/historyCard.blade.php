@extends('Admin.Layout.App')
@section('title', 'Lịch sử nạp thẻ cào')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lịch sử nạp thẻ cào</h4>
                    <div class="table-responsive">
                        <div id="" class="dataTables_wrapper w-100 overflow-x-auto overflow-y-hidden">
                            <table id="testds"
                                class="table border table-striped table-bordered display text-nowrap dataTable responsive w-100"
                                aria-describedby="file_export_info">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tài khoản</th>
                                        <th>Loại thẻ</th>
                                        <th>Mệnh giá</th>
                                        <th>Serial</th>
                                        <th>Mã thẻ</th>
                                        <th>Thực nhận</th>
                                        <th>Trạng thái</th>
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
@endsection
@section('script')
    <script>
        createDataTable('#testds', '{{ route('admin.list', 'history-card') }}', [{
            data: 'id'
        }, {
            data: 'username'
        }, {
            data: 'card_type'
        }, {
            data: 'card_amount'
        }, {
            data: 'card_serial'
        }, {
            data: 'card_code'
        }, {
            data: 'card_real_amount'
        },
        {
            data: 'status'
        },
        {
            data: 'created_at'
        }])
    </script>
@endsection
