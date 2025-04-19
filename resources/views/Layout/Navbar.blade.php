<!-- Sidebar Start -->
<aside class="left-sidebar" style="width: 270px;">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="text-nowrap logo-img">
                <img src="{{ DataSite('logo') }}" class="dark-logo" width="230" alt="" />
                <img src="{{ DataSite('logo') }}" class="light-logo" width="230" alt="" />
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar style="height: 900px;">
            <ul id="sidebarnav">
                <!-- ============================= -->
                <!-- Home -->
                <!-- ============================= -->
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Thông tin</span>
                </li>
                <!-- =================== -->
                <!-- Dashboard -->
                <!-- =================== -->
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('home') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/business-report.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Trang chủ</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('profile') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/profile.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Thông tin tài khoản</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('recharge.transfer') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/wallet.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Nạp tiền tài khoản</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('user.history') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/history.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Nhật kí hoạt động</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('tickets') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/tickets.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Phiếu hỗ trợ</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('client.refund.index') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/refund.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Lịch sử hoàn tiền</span>
                    </a>
                </li>
                @if (getDomain() == env('PARENT_SITE'))
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('create.website') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/development.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Tạo Site Con</span>
                    </a>
                </li>
                @endif
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('user.level') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/level.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Cấp bậc Tài khoản</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('user.affiliates') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/affiliates.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Chương trình Affiliates</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Các dịch vụ</span>
                </li>
                @if (getDomain() == env('PARENT_SITE'))
                <!--<li class="sidebar-item">-->
                <!--    <a class="sidebar-link" href="{{ route('service.price') }}" aria-expanded="false">-->
                <!--        <span>-->
                <!--            <img src="/assets/images/tag.png" width="25" alt="">-->
                <!--        </span>-->
                <!--        <span class="hide-menu">Bảng giá dịch vụ</span>-->
                <!--    </a>-->
                <!--</li>-->
                @endif
                @inject('service_social', 'App\Models\ServiceSocial')
                @inject('service', 'App\Models\Service')

                @foreach ($service_social->where('domain', env('PARENT_SITE'))->where('status', 'show')->get() as $item)
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                        <span class="d-flex">
                            <img src="{{ $item->image }}" width="25" alt="">
                        </span>
                        <span class="hide-menu">{{ $item->name }}</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        @foreach ($service->where('domain', env('PARENT_SITE'))->where('status', 'show')->where('service_social', $item->slug)->get() as $sv)
                        <li class="sidebar-item">
                            <style>
                                .sidebar-link {
                                    color: rgb({{ rand(0, 255) }}, {{ rand(0, 255) }}, {{ rand(0, 255) }});
                                }
                            </style>
                            <a href="{{ route('service.view', ['social' => $item->slug, 'service' => $sv->slug]) }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">{{ $sv->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">CÔNG CỤ & HỖ TRỢ</span>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                        <span class="d-flex">
                            <img src="/assets/images/settings.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Công cụ miễn phí</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ route('tool.uid') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Get Link/UID</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#comming-soon" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Kiểm tra tên miền</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                        <span class="d-flex">
                            <img src="/assets/images/customer-service.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Liên hệ hỗ trợ</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a href="{{ DataSite('facebook') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Facebook</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ DataSite('zalo') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Zalo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ DataSite('telegram') }}" target="_blank" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Telegram</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="https://documenter.getpostman.com/view/21752356/2s93sf4Bvu" target="_blank" aria-expanded="false">
                        <span>
                            <img src="/assets/images/computer.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Tài liệu API</span>
                    </a>
                </li>
                @if (Auth::user()->position == 'admin')
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
                        <span>
                            <img src="/assets/images/pie-chart.png" width="25" alt="">
                        </span>
                        <span class="hide-menu">Trang quản trị</span>
                    </a>
                </li>
                @endif
            </ul>
            <div class="unlimited-access hide-menu bg-light-primary position-relative my-7 rounded">
                <div class="d-flex">
                    <div class="unlimited-access-title">
                        <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Nhận nhiều ưu đãi!</h6>
                        <a href="{{ route('user.level') }}" class="btn btn-primary fs-2 fw-semibold lh-sm">Nâng
                            cấp</a>
                    </div>
                    <div class="unlimited-access-img">
                        <img src="/dist/images/backgrounds/rocket.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="empty-space" style="height: 350px;"></div>
        </nav>
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
<!--  Main wrapper -->
<div class="body-wrapper">
    <!--  Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="ti ti-search"></i>
                    </a>
                </li>
            </ul>
            <div class="d-block d-lg-none">
                <img src="{{ DataSite('logo') }}" class="light-logo" width="230" alt="" />
            </div>
            <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="p-2">
                    <i class="ti ti-dots fs-7"></i>
                </span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center justify-content-between">
                    <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                        <i class="ti ti-align-justified fs-7"></i>
                    </a>
                    <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link notify-badge nav-icon-hover" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <i class="ti ti-basket"></i>
                                <span class="badge rounded-pill bg-danger fs-2">2</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-bell-ringing"></i>
                                <div class="notification bg-primary rounded-circle"></div>
                            </a>
                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                                <div class="d-flex align-items-center justify-content-between py-3 px-7">
                                    <h5 class="mb-0 fs-5 fw-semibold">Thông Báo</h5>
                                    <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">5 Thông Báo</span>
                                </div>
                                <div class="message-body" data-simplebar>
                                    <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                        <span class="me-3">
                                            <img src="/dist/images/profile/user-1.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <h6 class="mb-1 fw-semibold">M-TP</h6>
                                            <span class="d-block">Dịch vụ lên nhanh.</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                        <span class="me-3">
                                            <img src="/dist/images/profile/user-2.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <h6 class="mb-1 fw-semibold">Amee</h6>
                                            <span class="d-block">Đơn lên nhanh, uy tín !!!</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                        <span class="me-3">
                                            <img src="/dist/images/profile/user-3.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <h6 class="mb-1 fw-semibold">Admin</h6>
                                            <span class="d-block">Cam kết uy tín, lên nhanh.</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                        <span class="me-3">
                                            <img src="/dist/images/profile/user-4.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <h6 class="mb-1 fw-semibold">Mono</h6>
                                            <span class="d-block">Website làm ăn uy tín.</span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                        <span class="me-3">
                                            <img src="/dist/images/profile/user-5.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <h6 class="mb-1 fw-semibold">Đức Phúc</h6>
                                            <span class="d-block">Dịch vụ giá rẻ còn lên nhanh nữa !!</span></span>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                                        <span class="me-3">
                                            <img src="/dist/images/profile/user-1.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                                        </span>
                                        <div class="w-75 d-inline-block v-middle">
                                            <h6 class="mb-1 fw-semibold">Roman Joined the Team!</h6>
                                            <span class="d-block">Congratulate him</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="py-6 px-7 mb-1">
                                    <button class="btn btn-outline-primary w-100"> Xem Tất Cả Thông Báo
                                    </button>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <div class="user-profile-img">
                                        <img src="{{ Auth::user()->avatar }}" class="rounded-circle" width="35" height="35" alt="" />
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                                <div class="profile-dropdown position-relative" data-simplebar>
                                    <div class="py-3 px-7 pb-0">
                                        <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                                    </div>
                                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                                        <img src="{{ Auth::user()->avatar }}" class="rounded-circle" width="80" height="80" alt="" />
                                        <div class="ms-3">
                                            <h5 class="mb-1 fs-3">{{ Auth::user()->name }}</h5>
                                            <span class="mb-1 d-block text-dark">{{ level(Auth::user()->level, false) }}</span>
                                            <p class="mb-0 d-flex text-dark align-items-center gap-2">
                                                <i class="ti ti-mail fs-4"></i> {{ Auth::user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="message-body">
                                        <a href="profile" class="py-8 px-7 mt-8 d-flex align-items-center">
                                            <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-account.svg" alt="" width="24" height="24">
                                            </span>
                                            <div class="w-75 d-inline-block v-middle ps-3">
                                                <h6 class="mb-1 bg-hover-primary fw-semibold"> Thông tin tài khoản
                                            </div>
                                        </a>
                                        <a href="user/history" class="py-8 px-7 d-flex align-items-center">
                                            <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                                                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-tasks.svg" alt="" width="24" height="24">
                                            </span>
                                            <div class="w-75 d-inline-block v-middle ps-3">
                                                <h6 class="mb-1 bg-hover-primary fw-semibold">Lịch sử tạo đơn</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="d-grid py-4 px-7 pt-8">
                                        <div class="upgrade-plan bg-light-primary position-relative overflow-hidden rounded-4 p-4 mb-9">
                                            <div class="row">
                                                <div class="col-6">
                                                    <h5 class="fs-4 mb-3 w-75 fw-semibold text-dark">Nhận nhiều ưu đãi
                                                    </h5>
                                                    <a href="{{ route('user.level') }}" class="btn btn-primary text-white">Nâng cấp</a>
                                                </div>
                                                <div class="col-6">
                                                    <div class="m-n4">
                                                        <img src="/dist/images/backgrounds/unlimited-bg.png" alt="" class="w-100">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('logout') }}" class="btn btn-outline-primary">Đăng xuất</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!--  Header End -->
    <div class="container-fluid">
        <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
                <div class="row align-items-center">
                    <div class="col-9">
                        <h4 class="fw-semibold mb-8">@yield('title')</h4>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a class="text-muted" href="#">Trang chủ</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">@yield('title')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-3">
                        <div class="text-center mb-n5">
                            <img src="/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield('content')