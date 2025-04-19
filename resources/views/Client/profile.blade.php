@extends('Layout.App')
@section('title', 'Thông tin cá nhân')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card overflow-hidden">
                <div class="card-body p-0">
                    <img src="/dist/images/backgrounds/profilebg.jpg" alt="" class="img-fluid">
                    <div class="row align-items-center">
                        <div class="col-lg-4 order-lg-1 order-2">
                            <div class="d-flex align-items-center justify-content-around m-4">
                                <div class="text-center">
                                    <i class="ti ti-businessplan fs-6 d-block mb-2"></i>
                                    <h4 class="mb-0 fw-semibold lh-1">{{ Auth::user()->balance }}</h4>
                                    <p class="mb-0 fs-4">Số dư</p>
                                </div>
                                <div class="text-center">
                                    <i class="ti ti-businessplan fs-6 d-block mb-2"></i>
                                    <h4 class="mb-0 fw-semibold lh-1">{{ Auth::user()->total_recharge }}</h4>
                                    <p class="mb-0 fs-4">Tổng nạp</p>
                                </div>
                                <div class="text-center">
                                    <i class="ti ti-businessplan fs-6 d-block mb-2"></i>
                                    <h4 class="mb-0 fw-semibold lh-1">{{ Auth::user()->total_deduct }}</h4>
                                    <p class="mb-0 fs-4">Tổng trừ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-n3 order-lg-2 order-1">
                            <div class="mt-n5">
                                <div class="d-flex align-items-center justify-content-center mb-2">
                                    <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle"
                                        style="width: 110px; height: 110px;";>
                                        <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden"
                                            style="width: 100px; height: 100px;";>
                                            <img src="{{ Auth::user()->avatar }}" alt="" class="w-100 h-100">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h5 class="fs-5 mb-0 fw-semibold">{{ Auth::user()->name }}</h5>
                                    <p class="mb-0 fs-4">{{ level(Auth::user()->level, false) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 order-last">
                            <ul
                                class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start my-3 gap-3">
                                <li class="position-relative">
                                    <a class="text-white d-flex align-items-center justify-content-center bg-primary p-2 fs-4 rounded-circle"
                                        href="{{ Auth::user()->facebook }}" target="_black" width="30" height="30">
                                        <i class="ti ti-brand-facebook"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2"
                        id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                                id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button"
                                role="tab" aria-controls="pills-profile" aria-selected="true">
                                <i class="ti ti-user-circle me-2 fs-6"></i>
                                <span class="d-none d-md-block">Thông tin</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                                id="pills-followers-tab" data-bs-toggle="pill" data-bs-target="#pills-followers"
                                type="button" role="tab" aria-controls="pills-followers" aria-selected="false">
                                <i class="ti ti-shield-lock me-2 fs-6"></i>
                                <span class="d-none d-md-block">Bảo mật</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6"
                                id="pills-friends-tab" data-bs-toggle="pill" data-bs-target="#pills-friends" type="button"
                                role="tab" aria-controls="pills-friends" aria-selected="false">
                                <i class="ti ti-settings-bolt me-2 fs-6"></i>
                                <span class="d-none d-md-block">Xác thực tài khoản</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-profile" role="tabpanel"
                    aria-labelledby="pills-profile-tab" tabindex="0">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card shadow-none border">
                                <div class="card-body">
                                    <h4 class="fw-semibold mb-3">Thông tin cơ bản</h4>
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex align-items-center gap-3 mb-4">
                                            <i class="ti ti-user-pin text-dark fs-6"></i>
                                            <h6 class="fs-4 fw-semibold mb-0">{{ Auth::user()->name }}</h6>
                                        </li>
                                        <li class="d-flex align-items-center gap-3 mb-4">
                                            <i class="ti ti-mail text-dark fs-6"></i>
                                            <h6 class="fs-4 fw-semibold mb-0">{{ Auth::user()->email }}</h6>
                                        </li>
                                        <li class="d-flex align-items-center gap-3 mb-4">
                                            <i class="ti ti-wallet text-dark fs-6"></i>
                                            <h6 class="fs-4 fw-semibold mb-0">{{ number_format(Auth::user()->balance) }}
                                                VNĐ</h6>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-5">
                                        <h4 class="card-title">Thông tin cá nhân</h4>
                                    </div>
                                    <div class="mb-3">
                                        <form action="{{ route('update-profile', 'profile') }}" method="POST"
                                            request="lbd">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-info"
                                                    name="name" value="{{ Auth::user()->name }}" placeholder="Name">
                                                <label><i class="ti ti-user me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Họ và tên</span></label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-info" disabled
                                                    value="{{ Auth::user()->username }}" placeholder="Username">
                                                <label><i class="ti ti-user me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Tài khoản</span></label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-info" disabled
                                                    value="{{ Auth::user()->email }}" placeholder="Username">
                                                <label><i class="ti ti-mail me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Email</span></label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-info"
                                                    name="image" value="{{ Auth::user()->avatar }}"
                                                    placeholder="Avatar">
                                                <label><i class="ti ti-photo-check me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Link ảnh đại
                                                        diện</span></label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-info" disabled
                                                    value="{{ number_format(Auth::user()->balance) }}"
                                                    placeholder="Balance">
                                                <label><i class="ti ti-coins me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Số dư</span></label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-info" disabled
                                                    value="{{ number_format(Auth::user()->total_recharge) }}"
                                                    placeholder="Balance">
                                                <label><i class="ti ti-coins me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Tổng nạp</span></label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-info" disabled
                                                    value="{{ number_format(Auth::user()->total_deduct) }}"
                                                    placeholder="Balance">
                                                <label><i class="ti ti-coins me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Tổng trừ</span></label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-info"
                                                    name="facebook" value="{{ Auth::user()->facebook }}"
                                                    placeholder="Facebook">
                                                <label><i class="ti ti-brand-facebook me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Link Facebook</span></label>
                                            </div>
                                            <div class="form-floating mb-3 cursor-pointer">
                                                <input type="text" class="form-control border border-info"
                                                    id="api_token" onclick="coppy('api_token')"
                                                    value="{{ Auth::user()->api_token }}" readonly>
                                                <label><i class="ti ti-api me-2 fs-4 text-info"></i><span
                                                        class="border-start border-info ps-3">Api token</span></label>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary col-12">
                                                    <i class="ti ti-device-floppy me-2 fs-4"></i>
                                                    Lưu thông tin
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-followers" role="tabpanel" aria-labelledby="pills-followers-tab"
                    tabindex="0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h4 class="card-title">Thay đổi mật khẩu</h4>
                                    </div>
                                    <form action="{{ route('update-profile', 'change-password') }}" method="POST"
                                        request="lbd">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control border border-primary"
                                                placeholder="Mật khẩu cũ" name="old_password">
                                            <label><i class="ti ti-lock me-2 fs-4 text-primary"></i><span
                                                    class="border-start border-primary ps-3">Mật khẩu cũ</span></label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control border border-primary"
                                                placeholder="Mật khẩu mới" name="new_password">
                                            <label><i class="ti ti-lock me-2 fs-4 text-primary"></i><span
                                                    class="border-start border-primary ps-3">Mật khẩu
                                                    mới</span></label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control border border-primary"
                                                placeholder="Mật khẩu mới" name="confirm_password">
                                            <label><i class="ti ti-lock me-2 fs-4 text-primary"></i><span
                                                    class="border-start border-primary ps-3">Nhập lại mật khẩu
                                                    mới</span></label>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary col-12">
                                                <i class="ti ti-lock me-2 fs-4"></i>
                                                Thay đổi mật khẩu
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-friends" role="tabpanel" aria-labelledby="pills-friends-tab"
                    tabindex="0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h4 class="card-title">Cấu hình tài khoản</h4>
                                    </div>
                                    <form action="{{ route('update-profile', 'update-telegram') }}" method="POST"
                                        request="lbd">
                                        <div class="form-group mb-3 row">
                                            <label for="" class="form-label col-md-3">Trạng thái Telegram</label>
                                            <div class="col-md-9">
                                                @if (Auth::user()->telegram_verified == 'yes')
                                                    <span>
                                                        <i class="ti ti-circle-check text-success fs-5"></i>
                                                        Đã liên kết
                                                    </span>
                                                    <div class="mt-3">
                                                        <b class="text-primary">Nhận thông báo từ telegram</b>
                                                        <div class="form-check">
                                                            @php
                                                                if (Auth::user()->telegram_notice == 'on') {
                                                                    $checked = 'checked';
                                                                } else {
                                                                    $checked = '';
                                                                }
                                                            @endphp
                                                            <input type="checkbox" class="form-check-input"
                                                                name="isNotice" {{ $checked }}
                                                                id="notice-tele">
                                                            <label class="form-check-label" for="notice-tele">Nhận thông
                                                                báo</label>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span>
                                                        <i class="ti ti-x text-danger fs-5"></i>
                                                        Chưa liên kết <b class="text-primary">(Liên kết telegram nhận ngay
                                                            {{ number_format(DataSite('balance_telegram')) }} vnđ)</b>
                                                        <!--<p>Nhấn vào <a href="{{ DataSite('telegram_bot') }}">Đây</a> để-->
                                                        <!--    liên kết</p>-->
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary col-12">
                                                <i class="ti ti-lock me-2 fs-4"></i>
                                                Lưu dữ liệu
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script></script>
@endsection
