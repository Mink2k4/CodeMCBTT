<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!--  Title -->
    <title>Đăng kí tài khoản</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{ DataSite('description') }}" />
    <meta name="keywords" content="{{ DataSite('keyword') }}" />
    <meta name="title" content="{{ DataSite('title') }}" />
    <link rel="shortcut icon" type="image/png" href="{{ DataSite('favicon') }}" />
    <meta property="og:image" content="{{ DataSite('image_seo') }}" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ DataSite('favicon') }}" />
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="/dist/css/style.min.css" />
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ DataSite('favicon') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ DataSite('favicon') }}" alt="loader" class="lds-ripple img-fluid" />
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
                                <a href="index.html" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                                    <img src="{{ DataSite('logo') }}"
                                        width="230" alt="">
                                </a>
                                <div class="row">
                                    <div class="col-6 mb-2 mb-sm-0">
                                        <a class="btn btn-white text-dark border fw-normal d-flex align-items-center justify-content-center rounded-2 py-8"
                                            href="javascript:void(0)" role="button">
                                            <img src="/dist/images/svgs/google-icon.svg"
                                                alt="" class="img-fluid me-2" width="18" height="18">
                                            <span class="d-none d-sm-block me-1 flex-shrink-0">Tiếp tục với</span>Google
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a class="btn btn-white text-dark border fw-normal d-flex align-items-center justify-content-center rounded-2 py-8"
                                            href="javascript:void(0)" role="button">
                                            <img src="/dist/images/svgs/facebook-icon.svg"
                                                alt="" class="img-fluid me-2" width="18" height="18">
                                            <span class="d-none d-sm-block me-1 flex-shrink-0">Tiếp tục với</span>FB
                                        </a>
                                    </div>
                                </div>
                                <div class="position-relative text-center my-4">
                                    <p class="mb-0 fs-4 px-3 d-inline-block bg-white z-index-5 position-relative">
                                        Hoặc đăng kí</p>
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

                                <form action="{{ route('register.post') }}" method="POST">
                                    @csrf
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="name"
                                            value="{{ old('name') }}" placeholder="Nhập họ tên của bạn">
                                        <label><i class="ti ti-user-check me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Họ và tên</span></label>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="email"
                                            value="{{ old('email') }}" placeholder="Email">
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
                                    <button class="btn btn-primary w-100 py-8 mb-4 rounded-2">Đăng kí tài
                                        khoản</button>
                                    <div class="d-flex align-items-center">
                                        <p class="fs-4 mb-0 text-dark">Bạn đã có tài khoản?</p>
                                        <a class="text-primary fw-medium ms-2" href="{{ route('login') }}">Đăng
                                            nhập</a>
                                    </div>
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
