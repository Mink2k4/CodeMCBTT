@extends('Layout.App')
@section('title', 'Tạo website riêng')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tạo website riêng</h4>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading">
                                <button class="accordion-button collapsed bg-primary text-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false"
                                    aria-controls="collapseOne">
                                    Hướng dẫn tạo Website
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="heading"
                                data-bs-parent="#accordionExample" style="">
                                <div class="accordion-body">
                                    <p class="fw-semobild"> - Bước 1 : Bạn cần phải có tên miền, nếu chưa bạn có thể mua tên
                                        miền tại tenten.vn (đọc
                                        lưu ý trước khi mua).</p>

                                    <p class="fw-semobild">- Bước 2 : Trỏ Nameserver1: {{ env('NAME_SERVER1') }}</p>

                                    <p class="fw-semobild">- Bước 3 : Trỏ Nameserver2: {{ env('NAME_SERVER2') }}</p>

                                    <p class="fw-semobild">- Bước 4 : Sau khi đã trỏ Nameserver bạn hãy liên hệ zalo: <a
                                            href="{{ DataSite('zalo') }}">{{ DataSite('zalo') }}</a> để
                                        hỗ trợ kích hoạt.</p>

                                    <p class="fw-semobild">- Bước 5 : Truy cập vào trang của bạn và nhập api token (lưu ý
                                        trước lúc kích hoạt api
                                        token không được để lộ tên miền tránh bị kích hoạt tài khoản admin).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion mb-3" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading2">
                                <button class="accordion-button collapsed bg-warning text-white" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseOne2" aria-expanded="false"
                                    aria-controls="collapseOne">
                                    Một số lưu ý khi tạo website
                                </button>
                            </h2>
                            <div id="collapseOne2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                data-bs-parent="#accordionExample2" style="">
                                <div class="accordion-body">
                                    <p>- Bạn phải đạt cấp bậc cộng tác viên hoặc đại lý mới có thể tạo web con!</p>
                                    <p>- Nghiêm cấm các tiên miền có chữ : Facebook, Instagram để tránh bị vi phạm bản
                                        quyền.</p>
                                    <p>- Khách hàng tạo tài khoản và sử dụng dịch vụ ở site con. Tiền sẽ trừ vào tài khoản
                                        của đại lý ở site chính. Vì thế để khách hàng mua được tài khoản đại lý phải còn số
                                        dư.</p>
                                    <p>- Chúng tôi hỗ trợ mục đích kinh doanh của tất cả cộng tác viên và đại lý!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb3">
                        <div class="input-group border rounded-1">
                            <input type="text" class="form-control border-0" id="api_token"
                                value="{{ Auth::user()->api_token }}" readonly>
                            <span class="input-group-text bg-transparent px-6" id="basic-addon1"><button type="button"
                                    id="btnReload" class="btn btn-primary">
                                    <i class="ti ti-reload"></i>
                                </button></span>
                        </div>
                    </div>
                    <form action="{{ route('create.website.post') }}" method="POST">
                        @csrf
                        <input type="text" value="{{ Auth::user()->api_token }}" name="api_token" hidden>
                        <div class="mb-3">
                            <label for="domain" class="form-label">Tên miền</label>
                            <input type="text" class="form-control" id="domain" name="domain" placeholder="Tên miền"
                                value="{{ $sitecon->domain_name }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Tạo website</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#btnReload').click(function() {
            $.ajax({
                url: "{{ route('user.action', 'change-token') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                dataType: "json",
                beforeSend: function() {
                    $('#btnReload').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý..').prop(
                        'disabled', true);
                },
                complete: function() {
                    $('#btnReload').html('<i class="ti ti-reload"></i>').prop('disabled', false);
                },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#api_token').val(data.api_token);
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: data.message,
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message,
                        });
                    }
                },
            });
        });
    </script>
@endsection
