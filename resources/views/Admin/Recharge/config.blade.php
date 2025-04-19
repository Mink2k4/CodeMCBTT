@extends('Admin.Layout.App')
@section('title', 'Cấu hình nạp tiền')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Thêm nạp tiền</h4>
                    <form action="{{ route('admin.recharge.config.post') }}" method="POST" request="lbd">
                        <div class="form-floating mb-3">
                            <select name="type" id="" class="form-select border border-info">
                                <option value="mbbank">Ngân hàng MBbank</option>
                                <option value="vietcombank">Ngân hàng Vietcombank</option>
                                <option value="momo">Ví Momo</option>
                                <option value="perfectmoney">PerfectMoney</option>
                                <option value="tether">Tether</option>
                                <option value="bidv">BIDV</option>
                                <option value="cake">CAKE By VPBank</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Loại</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="name" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Tên tài khoản</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="account" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Tài khoản</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="stk" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Số tài khoản</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control border border-info" name="password"
                                placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Mật khẩu (nếu có)</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="api_token"
                                placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Token Api</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="quy_doi"
                                placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Quy đổi</span></label>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary col-12">Thêm nạp tiền</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Cấu hình giảm giá</h4>
                    <form action="{{ route('admin.recharge.promotion.post') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <select type="text" class="form-select border border-info" name="action">
                                <option value="show" @if (DataSite('show_promotion') == 'show') selected @endif>Hiển thị</option>
                                <option value="hide" @if (DataSite('show_promotion') == 'hide') selected @endif>Ẩn</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Hiển thị thông báo khuyến mãi</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="promotion"
                                value="{{ DataSite('recharge_promotion') }}" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Khuyến mãi </span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control border border-info" name="start_promotion"
                                value="{{ DataSite('start_promotion') }}" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Thời gian bắt đầu</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control border border-info" name="end_promotion"
                                value="{{ DataSite('end_promotion') }}" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Thời gian kết thúc</span></label>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary col-12">Lưu cấu hình nạp tiền</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Danh sách ngân hàng</h4>
                    <div class="table-responsive">
                        <div id="" class="dataTables_wrapper">
                            <table id="testds"
                                class="table border table-striped table-bordered display text-nowrap dataTable responsive w-100"
                                aria-describedby="file_export_info">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Loại</th>
                                        <th>Tên tài khoản</th>
                                        <th>Tài khoản</th>
                                        <th>Số tài khoản</th>
                                        <th>Logo</th>
                                        <th>Quy đổi</th>
                                        <th>Thao tác</th>
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
        createDataTable('#testds', '{{ route('admin.list', 'list-recharge') }}', [{
            data: 'id'
        }, {
            data: 'type'
        }, {
            data: 'account_name'
        }, {
            data: 'account'
        }, {
            data: 'account_number'
        }, {
            data: 'logo',
        }, {
            data: 'quy_doi',
            render: function(data, type, row) {
                return `<img src="${data}" alt="" width="50px">`
            }
        }, {
            data: null,
            render: function(data, type, row) {
                return `
                        <a href="{{ route('admin.recharge.delete', 'id') }}" class="btn btn-danger">Xóa</a>
                    `.replace('id', data.id)
            }
        }])
    </script>
@endsection
