@extends('Layout.App')
@section('title', 'Cấp bậc tài khoản')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h2 class="fw-bolder mb-0 fs-8 lh-base">Mỗi các cấp bậc cao sẽ được ưu đãi cao và nhiều giá trị khác
                    </h2>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-sm-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-bolder text-uppercase fs-2 d-block mb-7">Cộng tác viên</span>
                            <div class="my-4">
                                <img src="/dist/images/backgrounds/silver.png" alt="" class="img-fluid"
                                    width="80" height="80">
                            </div>
                            <h2 class="fw-bolder fs-7 mb-3">{{ number_format(DataSite('collaborator')) }} VNĐ</h2>
                            <ul class="list-unstyled mb-7">
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Được giảm các dịch vụ</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Được hỗ trợ nhanh gọn</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Được hỗ trợ tạo website riêng</span>
                                </li>
                            </ul>
                            <button class="btn btn-primary fw-bolder rounded-2 py-6 w-100 text-capitalize">Nâng cấp ngay</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-bolder text-uppercase fs-2 d-block mb-7">Đại lý</span>
                            <div class="my-4">
                                <img src="/dist/images/backgrounds/bronze.png" alt="" class="img-fluid"
                                    width="80" height="80">
                            </div>
                            <div class="d-flex mb-3">
                                <h2 class="fw-bolder fs-7 mb-3">{{ number_format(DataSite('agency')) }} VNĐ</h2>
                            </div>
                            <ul class="list-unstyled mb-7">
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Nạp đủ và thăng cấp tự động</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Được tạo website riêng</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-primary">Giá được ưu đã tốt.</span>
                                </li>
                            </ul>
                            <button class="btn btn-primary fw-bolder rounded-2 py-6 w-100 text-capitalize">Nâng cấp ngay</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-bolder text-uppercase fs-2 d-block mb-7">Nhà phân phối</span>
                            <div class="my-4">
                                <img src="/dist/images/backgrounds/gold.png" alt="" class="img-fluid"
                                    width="80" height="80">
                            </div>
                            <div class="d-flex mb-3">
                                <h2 class="fw-bolder fs-12 ms-2 mb-0">{{ number_format(DataSite('distributor')) }} VNĐ</h2>
                            </div>
                            <ul class="list-unstyled mb-7">
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Nạp đủ và thăng cấp tự động</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">1 giao dịch 50 triệu hoặc tổng nạp 250 triệu</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Được tạo website riêng</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Giá được ưu đã tốt nhất, hỗ trợ đặc biệt</span>
                                </li>
                                <li class="d-flex align-items-center gap-2 py-2">
                                    <i class="ti ti-check text-primary fs-4"></i>
                                    <span class="text-dark">Được bổ sung các tính năng yêu cầu.</span>
                                </li>
                            </ul>
                            <button class="btn btn-primary fw-bolder rounded-2 py-6 w-100 text-capitalize">Nâng cấp ngay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
