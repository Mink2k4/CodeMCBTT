@extends('Admin.Layout.App')
@section('title', 'Trang thống kê')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow">
                <div class="d-flex align-items-center bg-white rounded p-3">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-4 bg-primary rounded-2">
                                <i class="ti ti-users text-white fs-7"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-dark fs-5 fw-bold">Tổng thành viên</div>
                                <span class="text-muted fw-normal"><b
                                        class="text-danger">{{ number_format($total_user) }}</b> thành
                                    viên</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="d-flex align-items-center bg-white rounded p-3">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-4 bg-primary rounded-2">
                                <i class="ti ti-coins text-white fs-7"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-dark fs-5 fw-bold">Tổng số dư</div>
                                <span class="text-muted fw-normal"><b
                                        class="text-danger">{{ number_format($total_balance) }}</b> VND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="d-flex align-items-center bg-white rounded p-3">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-4 bg-primary rounded-2">
                                <i class="ti ti-credit-card text-white fs-7"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-dark fs-5 fw-bold">Tổng nạp</div>
                                <span class="text-muted fw-normal"><b
                                        class="text-danger">{{ number_format($total_recharge) }}</b> VND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="d-flex align-items-center bg-white rounded p-3">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-4 bg-primary rounded-2">
                                <i class="ti ti-shopping-cart text-white fs-7"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-dark fs-5 fw-bold">Tổng đơn hàng</div>
                                <span class="text-muted fw-normal"><b
                                        class="text-danger">{{ number_format($total_order) }}</b> Đơn</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="d-flex align-items-center bg-white rounded p-3">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-4 bg-primary rounded-2">
                                <i class="ti ti-users text-white fs-7"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-dark fs-5 fw-bold">Thành viên hôm nay</div>
                                <span class="text-muted fw-normal"><b
                                        class="text-danger">{{ number_format($total_user_today) }}</b> thành
                                    viên</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="d-flex align-items-center bg-white rounded p-3">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-4 bg-primary rounded-2">
                                <i class="ti ti-shopping-cart text-white fs-7"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-dark fs-5 fw-bold">Đã dùng hôm nay</div>
                                <span class="text-muted fw-normal"><b
                                        class="text-danger">{{ number_format($total_deduct_today) }}</b> VND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="d-flex align-items-center bg-white rounded p-3">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-4 bg-primary rounded-2">
                                <i class="ti ti-credit-card text-white fs-7"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-dark fs-5 fw-bold">Đã nạp hôm nay</div>
                                <span class="text-muted fw-normal"><b
                                        class="text-danger">{{ number_format($total_recharge_today) }}</b> VND</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow">
                <div class="d-flex align-items-center bg-white rounded p-3">
                    <div class="d-flex flex-stack">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-4 bg-primary rounded-2">
                                <i class="ti ti-shopping-cart text-white fs-7"></i>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="text-dark fs-5 fw-bold">Đơn hàng hôm nay</div>
                                <span class="text-muted fw-normal"><b
                                        class="text-danger">{{ number_format($total_order_today) }}</b> Đơn</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title">Thống kê trạng thái đơn</h4>
                    <div class="mb-3" id="order-statistics">

                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title">Thông kê lượt truy cập trong <span class="text-warning fw-semibold">(30
                            Ngày)</span></h4>
                    <div class="mb-3" id="traffic-website">

                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
@section('css')

@endsection
@section('script')
    <script src="/dist/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script>
        var options_pie = {
            series: [{{ $order_completed }}, {{ $order_pending_order }}, {{ $order_active }}, {{ $order_suspended }}, {{ $order_failed }}, {{ $order_cancelled }}],
            chart: {
                fontFamily: '"Nunito Sans", sans-serif',
                width: 480,
                type: "pie",
            },
            colors: ["var(--bs-success)", "var(--bs-info)", "var(--bs-primary)", "var(--bs-secondary)",
                "var(--bs-warning)",
                "var(--bs-danger)"
            ],
            labels: ["Đơn hoàn thành", "Đang tạo đơn", "Đơn đang hoạt động", "Đơn tạm ngưng", "Đơn lỗi", "Đơn huỷ"],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200,
                    },
                    legend: {
                        position: "bottom",
                    },
                },
            }, ],
            legend: {
                labels: {
                    colors: ["#a1aab2"],
                },
            },
        }

        var chart_order = new ApexCharts(document.querySelector("#order-statistics"), options_pie);
        chart_order.render();

        var options_line = {
            series: [{
                name: "Lượt truy cập",
                data: [10, 90, 35, 51, 49, 62, 69, 91, 200],
            }, ],
            chart: {
                height: 350,
                type: "line",
                fontFamily: '"Nunito Sans",sans-serif',
                zoom: {
                    enabled: false,
                },
                toolbar: {
                    show: false,
                },
            },
            dataLabels: {
                enabled: false,
            },
            colors: ["var(--bs-primary)"],
            stroke: {
                curve: "straight",
            },
            grid: {
                row: {
                    colors: ["rgba(0,0,0,0.2)", "transparent"], // takes an array which will be repeated on columns
                    opacity: 0.5,
                },
                borderColor: "transparent",
            },
            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                ],
                labels: {
                    style: {
                        colors: [
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                        ],
                    },
                },
            },
            yaxis: {
                labels: {
                    style: {
                        colors: [
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                            "#a1aab2",
                        ],
                    },
                },
            },
            tooltip: {
                theme: "dark",
            },
        };

        var chart_basic = new ApexCharts(document.querySelector("#traffic-website"), options_line);
        chart_basic.render();
    </script>
@endsection
