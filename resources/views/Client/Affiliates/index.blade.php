@extends('Layout.App')

@section('content')
<div class="container mt-4">
    <div class="card">
        <!-- Header -->
        <div class="card-header bg-white text-dark shadow-sm">
            <h4 class="mb-0">Chương Trình Affiliate</h4>
        </div>

        <div class="card-body">
            <!-- Link giới thiệu -->
            <div class="mb-3">
                <label class="form-label fw-bold">Link Giới Thiệu</label>
                <input type="text" class="form-control text-dark" 
                       value="{{ route('user.affiliates', ['ref' => auth()->user()->id]) }}" readonly>
            </div>

            <!-- Thông tin thống kê -->
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Lượt truy cập</th>
                        <th>Đăng ký</th>
                        <th>Giới thiệu thành công</th>
                        <th>Tỉ lệ chuyển đổi</th>
                        <th>Tổng thu nhập</th>
                        <th>Thu nhập khả dụng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $visits }}</td>
                        <td>{{ $registrations }}</td>
                        <td>{{ $referrals }}</td>
                        <td>{{ number_format($conversionRate, 2) }}%</td>
                        <td>${{ number_format($totalEarnings, 2) }}</td>
                        <td>${{ number_format($availableEarnings, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Lịch sử thanh toán -->
            <h5 class="mt-4">Lịch Sử Thanh Toán</h5>
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Ngày thanh toán</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($payouts->isEmpty())
                        <tr>
                            <td colspan="3">Chưa có dữ liệu</td>
                        </tr>
                    @else
                        @foreach ($payouts as $payout)
                            <tr>
                                <td>{{ $payout->date }}</td>
                                <td>${{ number_format($payout->amount, 2) }}</td>
                                <td>{{ ucfirst($payout->status) }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
