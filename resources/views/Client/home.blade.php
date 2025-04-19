@extends('Layout.App')

@section('title', 'Trang chủ')

@section('content')

@yield('flower-animation')
    <div class="row">
        @if (DataSite('show_promotion') == 'show')
            <div class="col-md-12">
                <div class="card w-100 bg-light-secondary overflow-hidden shadow-none">
                    <div class="card-body py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-sm-6">
                                <h3 class="fw-semibold mb-9 fs-7">Khuyến mãi <span
                                        class="fw-semibold text-primary">{{ DataSite('recharge_promotion') }}%</span> Giá trị
                                    nạp tiền</h3>
                                <p class="mb-9 fs-4">
                                    Khuyến mãi giá trị nạp tiền từ ngày: <span
                                        class="text-primary fs-5 fw-semibold">{{ Date('d-m', strtotime(DataSite('start_promotion'))) }}</span>
                                    đến ngày: <span
                                        class="text-danger fs-5 fw-semibold">{{ Date('d-m', strtotime(DataSite('end_promotion'))) }}</span>
                                    Nạp ngay để nhận khuyến mãi nào!!
                                </p>
                                <a href="{{ route('recharge.transfer') }}" class="btn btn-secondary">Nạp tiền ngay</a>
                            </div>
                            <div class="col-sm-5">
                                <div class="position-relative mb-n5 text-center">
                                    <img src="../../dist/images/backgrounds/track-bg.png" alt="" class="img-fluid"
                                        style="width: 180px; height: 230px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100 bg-light-info overflow-hidden shadow-none">
                <div class="card-body position-relative">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="d-flex align-items-center mb-7">
                                <div class="rounded-circle overflow-hidden me-6">
                                    <img src="/dist/images/profile/user-1.jpg" alt="" width="40" height="40">
                                </div>
                            <style>
                                @keyframes rainbow {
                                    0% { color: red; }
                                    14% { color: orange; }
                                    28% { color: yellow; }
                                    42% { color: green; }
                                    57% { color: blue; }
                                    71% { color: indigo; }
                                    85% { color: violet; }
                                    100% { color: red; }
                                }
                        
                                .rainbow-text {
                                    animation: rainbow 5s linear infinite !important;
                                }
                            </style>
                            <h5 class="fw-semibold mb-0 fs-5">
                                Chào mừng bạn <b class="rainbow-text">{{ Auth::user()->name }}</b>
                            </h5>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="border-end pe-4 border-muted border-opacity-10">
                                    <h3 class="mb-1 fw-semibold fs-5 d-flex align-content-center">
                                        {{ number_format(Auth::user()->balance) }} VNĐ
                                    </h3>
                                    <p class="mb-0 text-dark">Số dư</p>
                                </div>
                                <div class="ps-4">
                                    <h3 class="mb-1 fw-semibold fs-5 d-flex align-content-center">
                                        {{ number_format(Auth::user()->total_recharge) }} VNĐ
                                    </h3>
                                    <p class="mb-0 text-dark">Tổng nạp</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="welcome-bg-img mb-n7 text-end">
                                <img src="" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card w-100" style="background-color: rgb(0, 214, 127);">
                        <div class="card-body">
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="p-2 bg-light-primary rounded-2 d-inline-block">
                                    <img src="/assets/images/user.png" alt="" class="img-fluid" width="34"
                                        height="34">
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1 fs-4 fw-semibold" style="color: white;">Cấp bậc</h5>
                                    <p class="fs-5 mb-0 fw-semibold text-primary" style="color: white !important;">{{ level(Auth::user()->level, false) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card w-100" style="background-color: rgb(38,42,46);">
                        <div class="card-body">
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="p-2 bg-light-primary rounded-2 d-inline-block">
                                    <img src="/assets/images/trolley.png" alt="" class="img-fluid" width="34"
                                        height="34">
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1 fs-4 fw-semibold" style="color: white;">Tổng mua</h5>
                                <p class="fs-5 mb-0 fw-semibold text-danger" style="color: white !important;">
                                    {{ number_format(Auth::user()->total_deduct) }}
                                </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-8">
            @foreach ($notification as $item)
                <div class="card">
                    <div class="card-body bg-light rounded-4">
                        <div class="d-flex justify-content-start align-items-center gap-2">
                            <div class="shrink-0 p-2 bg-light-secondary rounded-5">
                                <img src="/assets/images/administrator.png" class="img-fluid" width="44" alt="">
                            </div>
                            <div class="">
                                <h4 class="fw-semibold fs-4">{{ $item->name }}</h4>
                                <p class="text-secondary fw-semibold">{{ $item->created_at }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            {!! $item->content !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-md-12 col-lg-4">
            <div class="card w-100" style="background-color: rgb(0,125,136)">
                <div class="card-body mb-3">
                    <div class="mb-4">
                        <h5 class="card-title fw-semibold">Các hoạt động mới</h5>
                    </div>
                    <ul class="timeline-widget mb-0 position-relative mb-n5 overflow-auto" style="max-height: 430px;">
                        @foreach ($activities as $item)
                            <li class="timeline-item d-flex justify-content-start position-relative overflow-hidden">
                                <div class="timeline-time" style="color: white; text-align: end;">
                                    {{ date('d M Y', strtotime($item->created_at)) }}
                                </div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span
                                        class="timeline-badge border-2 border border-{{ $item->status }} flex-shrink-0 my-8"></span>
                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                </div>
                        <div class="timeline-desc fs-3 mt-n1" style="color: white;">{{ $item->content }}</div>                            
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div id="bs-example-modal-md" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myModalLabel">
                        Thông báo hệ thống
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! DataSite('notice') !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect"
                        data-bs-dismiss="modal">
                        Đóng
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<!-- Messenger Plugin chat Code -->
    <div id="fb-root"></div>

    <!-- Your Plugin chat code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "115587334831139");
      chatbox.setAttribute("attribution", "biz_inbox");
    </script>

    <!-- Your SDK code -->
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v17.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#bs-example-modal-md').modal('show')
        })
    </script>
@endsection
