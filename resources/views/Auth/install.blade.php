<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!--  Title -->
    <title>Cài đặt website</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Cài đặt website" />
    <meta property="og:image" content="{{ DataSite('image_seo') }}" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="" />
    <!--  Favicon -->
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="/dist/css/style.min.css" />
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <img src="/dist/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <img src="/dist/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <h4 class="mb-3 text-center">Bắt đầu cài đặt website của bạn</h4>
                                <div class="position-relative text-center my-4">
                                    <p
                                        class="mb-0 fs-4 px-3 d-inline-block bg-white text-dark z-index-5 position-relative">
                                        Nhập thông tin</p>
                                    <span
                                        class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
                                </div>


                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                        role="alert">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                        <strong>Lỗi - </strong> {{ $errors->first() }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                        role="alert">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                        <strong>Lỗi - </strong> {{ session('error') }}
                                    </div>
                                @endif
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show"
                                        role="alert">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                        <strong>Thành công - </strong> {{ session('success') }}
                                    </div>
                                @endif

                                <form action="{{ route('install.website.post') }}" method="POST">
                                    @csrf
                                    @if (getDomain() != env('PARENT_SITE'))
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control border border-info"
                                                name="api_token" value="{{ old('api_token') }}" placeholder="Api token">
                                            <label><i class="ti ti-user me-2 fs-4 text-info"></i><span
                                                    class="border-start border-info ps-3">Api token website
                                                    mẹ</span></label>
                                        </div>
                                    @endif
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="name"
                                            value="{{ old('name') }}" placeholder="Họ và tên">
                                        <label><i class="ti ti-user me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Họ và tên</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="email"
                                            value="{{ old('email') }}" placeholder="Địa chỉ Email">
                                        <label><i class="ti ti-mail me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Địa chỉ Email</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="username"
                                            value="{{ old('username') }}" placeholder="Username">
                                        <label><i class="ti ti-user me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Tài khoản</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control border border-info"
                                            name="password" placeholder="Password">
                                        <label><i class="ti ti-lock me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mật khẩu</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control border border-info"
                                            name="password_confirmation" placeholder="CPassword">
                                        <label><i class="ti ti-lock me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Nhập lại mật khẩu</span></label>
                                    </div>
                                    <button class="btn btn-primary w-100 py-8 mb-4 rounded-2">Kích hoạt
                                        website</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    
    <!--  Import Js Files -->
    <script src="/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="/dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--  core files -->
    <script src="/dist/js/app.min.js"></script>
    <script src="/dist/js/app.init.js"></script>
    <script src="/dist/js/app-style-switcher.js"></script>
    <script src="/dist/js/sidebarmenu.js"></script>

    <script src="/dist/js/custom.js"></script>
</body>

</html>
