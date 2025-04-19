@extends('Layout.App')
@section('title', 'Nạp tiền thẻ cào')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Nạp thẻ cào</h4>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="{{ route('recharge.transfer') }}" class="btn btn-outline-primary col-12 mb-2">Chuyển
                                khoản</a>

                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('recharge.card') }}" class="btn btn-primary col-12 mb-2">Thẻ cào</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="alert alert-primary">
                            <p class="fw-semibold">- Tự động duyệt thẻ và cộng tiền từ 5 - 15 phút, đúng sẽ được cộng tiền
                                và ngược lại!</p>
                            <p class="fw-semibold">- Vui lòng chọn đúng mệnh giá thẻ và nhà mạng giúp mình nhé, sai có thể
                                sẽ mất thẻ và không được cộng hoặc hoàn tiền!</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <form action="" method="POST" request="lbd">
                            <div class="form-group mb-3">
                                <label for="telco" class="form-label">Loại thẻ: </label>
                                <select name="telco" id="telco" onchange="rebill()" class="form-select">
                                    <option value="">-- Chọn Loại Thẻ --</option>
                                    <option value="VIETTEL">Viettel (Chiết khấu: {{ DataSite('card_discount') }}%)</option>
                                    <option value="MOBIFONE">Mobifone (Chiết khấu: {{ DataSite('card_discount') }}%)
                                    </option>
                                    <option value="VINAPHONE">Vinaphone (Chiết khấu: {{ DataSite('card_discount') }}%)
                                    </option>
                                    <option value="ZING">Zing (Chiết khấu: {{ DataSite('card_discount') }}%)</option>
                                    <option value="GATE">Gate (Chiết khấu: {{ DataSite('card_discount') }}%)</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="amount" class="form-label">Mệnh giá: </label>
                                <select name="amount" id="amount" onchange="rebill()" class="form-select">
                                    <option value="10000">10.000đ</option>
                                    <option value="20000">20.000đ</option>
                                    <option value="30000">30.000đ</option>
                                    <option value="50000">50.000đ</option>
                                    <option value="100000">100.000đ</option>
                                    <option value="200000">200.000đ</option>
                                    <option value="300000">300.000đ</option>
                                    <option value="500000">500.000đ</option>
                                    <option value="1000000">1.000.000đ</option>
                                </select>
                            </div>
                            <div class="mb-3 form-group">
                                <label for="serial" class="form-label">Mã serial: </label>
                                <input type="text" class="form-control" name="serial" id="serial"
                                    placeholder="Nhập mã serial">
                            </div>
                            <div class="form-group mb-3">
                                <label for="code">Mã thẻ: </label>
                                <input type="text" class="form-control" name="code" id="code"
                                    placeholder="Nhập mã thẻ">
                            </div>
                            <div class="b-3">
                                <div class="alert alert-primary bg-primary text-center text-white">
                                    <b class="fw-semibold" id="received">0</b> <br>
                                    <span class="fw-semibold">Số tiền thực nhận khi nạp thẻ <b
                                            class="fw-semibold text-warning" id="telco-name">VIETEL</b> mệnh giá <b
                                            class="fw-semibold text-danger" id="telco-amount">0</b></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary col-12">
                                    <i class="ti ti-credit-card fs-5"></i>
                                    Nạp thẻ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lịch sử nạp thẻ cào</h4>
                    <div class="mb-3">
                        <div class="table-responsive">
                            <div id="" class="dataTables_wrapper">
                                <table id="testds"
                                    class="table border table-striped table-bordered display text-nowrap dataTable w-100"
                                    aria-describedby="file_export_info">
                                    <thead>
                                        <!-- start row -->
                                        <tr>
                                            <th>ID</th>
                                            <th>Trạng thái</th>
                                            <th>Thời gian</th>
                                            <th>Nhà mạng</th>
                                            <th>Mệnh giá</th>
                                            <th>Mã serial</th>
                                            <th>Mã thẻ</th>
                                            <th>Thực nhận</th>
                                            <th>Thao tác</th>
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
        function rebill() {
            var telco = $('#telco').val();
            var amount = $('#amount').val();
            var received = 0;
            var telco_name = '';
            var telco_amount = 0;
            var discount = {{ DataSite('card_discount') }};

            telco_amount = amount - (amount * discount / 100);
            received = amount - (amount * discount / 100);
            $('#received').html(Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(received));
            $('#telco-name').html(telco_name);
            $('#telco-amount').html(Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(telco_amount));
        }
        createDataTable('#testds', '{{ route('user.list.action', 'history-card') }}', [{
                data: 'id'
            },
            {
                data: 'status'
            }, {
                data: 'created_at'
            }, {
                data: 'card_type'
            }, {
                data: 'card_amount'
            }, {
                data: 'card_serial'
            }, {
                data: 'card_code'
            }, {
                data: 'card_real_amount',
                render: function(data, type, row) {
                    return `<b class="text-success">${formatNumber(data)}đ</b>`
                }
            }, {
                data: null,
                render: function(data, type, row) {
                    return 'ds'
                }
            }
        ])
    </script>
@endsection
