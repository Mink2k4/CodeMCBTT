<!DOCTYPE html>
<html lang="en">

<head>
    <!--  Title -->
    <title>Khôi phục mật khẩu</title>
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
                            <div class="card-body pt-5">
                                <a href="/" class="text-nowrap logo-img text-center d-block mb-4">
                                    <img src="{{ DataSite('login') }}" width="180" alt="">
                                </a>
                                <div class="mb-5 text-center">
                                    <p class="mb-0 ">
                                        Vui lòng nhập mật khẩu mới 
                                    </p>
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

                                <form action="{{ route('reset.password.post', $token->token) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="passwordNew" class="form-label">Mật Khẩu mới</label>
                                        <input type="password" class="form-control" name="password"
                                            value="{{ old('password') }}" id="passwordNew">
                                    </div>
                                    <div class="mb-3">
                                        <label for="passwordNewConfirm" class="form-label">Xác nhận mật khẩu mới</label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                            value="{{ old('password_confirmation') }}" id="passwordNewConfirm">
                                    </div>

                                    <button class="btn btn-primary w-100 py-8 mb-3">Khôi phục mật khẩu</button>
                                    <a href="{{ route('login') }}"
                                        class="btn btn-light-primary text-primary w-100 py-8">Quay lại đăng nhập</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
