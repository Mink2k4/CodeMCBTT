@extends('Layout.App')
@section('title', 'Nạp tiền chuyển khoản')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Nạp tiền chuyển khoản</h4>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="{{ route('recharge.transfer') }}" class="btn btn-primary col-12 mb-2">Chuyển khoản</a>

                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('recharge.card') }}" class="btn btn-outline-primary col-12 mb-2">Thẻ cào</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="alert alert-danger">
                            <p class="fw-semibold">Nạp tiền rồi nhưng chưa có hoặc đợi quá lâu vui lòng nhắn tin với Admin
                                để được hỗ trợ nhanh nhất!</p>
                            <p class="fw-semibold">Nạp sai nội dung sẽ bị trừ 10% số tiền đã chuyển.</p>
                            <p class="fw-semibold">Nạp tối thiểu 10,000đ ( cố tình nạp dưới mức tối thiểu sẽ không hỗ trợ )
                            <p class="fw-semibold">Các phương thức thanh toán qua Momo, VCB, Mbbank sẽ tự động cộng tiền còn các phương thức thanh toán khác vui lòng liên hệ admin để được nạp thủ công
                            </p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            @foreach ($account as $item)
                                <div class="col-md-6">
                                    <div class="card border border-primary">
                                        <div class="mt-3 text-center">
                                            <img src="{{ $item->logo }}" width="90"
                                                class="img-fluid" alt="">
                                        </div>
                                        <div class="card-body text-center d-flex justify-content-center align-items-start">
                                            <div class="text-start">
                                                <p class="fs-4">Loại: <b class="text-primary cursor-pointer">{{ ucwords($item->type) }}</i>
                                                    </b>
                                                </p>
                                                <p class="fs-4">Số tài khoản: <b class="text-primary cursor-pointer" id="number_{{ $item->type }}" onclick="coppy('number_{{ $item->type }}')">{{ $item->account_number }} <i
                                                            class="ti ti-copy fs-5"></i> </b></p>
                                                <p class="fs-4">Chủ tài khoản: <b class="text-secondary">{{ $item->account_name }}</b></p>
                                                <p class="fs-4">Quy đổi: <b class="text-secondary">{{ $item->quy_doi }}</b></p>
                                                <div class="text-center">
                                                    <button
                                                        class="btn btn-sm rounded-2 btn-light-primary text-primary btn-lg px-4 fs-4 font-medium"
                                                        data-bs-toggle="modal" data-bs-target="#bs-example-modal-md{{ $item->type }}">
                                                        Hiển thị mã QR <i class="ti ti-qrcode"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bs-example-modal-md{{ $item->type }}" class="modal fade" tabindex="-1"
                                        aria-labelledby="bs-example-modal-md{{ $item->type }}" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header d-flex align-items-center">
                                                    <h4 class="modal-title" id="myModalLabel">
                                                        Mã QR Nạp tiền
                                                    </h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="w-100">
                                                        @if ($item->type == 'momo')
                                                        <img src="{{ $item->qr_code }}{{ DataSite('code_tranfer') }}{{ Auth::user()->id }}"
                                                            class="img-fluid" alt="">
                                                        @else
                                                        <img src="{{ $item->qr_code }}&addInfo={{ DataSite('code_tranfer') }}{{ Auth::user()->id }}"
                                                            class="img-fluid" alt="">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button"
                                                        class="btn btn-light-danger text-danger font-medium waves-effect"
                                                        data-bs-dismiss="modal">
                                                        Đóng
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <h4 class="text-center">Nội dung nạp tiền</h4>
                        <div class="alert alert-primary text-center">
                            <a href="javascript:;" onclick="coppy('tranfer_code')">
                                <b class="text-success text-hover-primary fs-6" id="tranfer_code">{{ DataSite('code_tranfer') }}{{ Auth::user()->id }}</b>
                                <i class="ti ti-copy fs-7"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lịch sử nạp tiền</h4>
                    <div class="mb-3">
                        <div class="table-responsive">
                            <div id="" class="dataTables_wrapper">
                                <table id="testds"
                                    class="table border table-striped table-bordered display text-nowrap dataTable"
                                    aria-describedby="file_export_info">
                                    <thead>
                                        <!-- start row -->
                                        <tr>
                                            <th>ID</th>
                                            <th>Thời gian</th>
                                            <th>Loại</th>
                                            <th>Mã giao dịch</th>
                                            <th>Người chuyển</th>
                                            <th>Thực nhận</th>
                                            <th>Nội dung</th>
                                        </tr>
                                        <!-- end row -->
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
        createDataTable('#testds', '{{ route('user.list.action', 'history-transfer') }}', [{
                data: 'id'
            },
            {
                data: 'created_at'
            },
            {
                data: 'type_bank'
            },
            {
                data: 'tranid'
            },
            {
                data: 'name_bank'
            },
            {
                data: 'real_amount',
                render: function(data, type, row){
                    return `<b class="text-success">${formatNumber(data)}đ</b>`
                }
            },
            {
                data: 'note'
            }
        ])
    </script>
@endsection
