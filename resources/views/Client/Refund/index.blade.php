@extends('Layout.App')
@section('title', 'Lịch sử Hoàn Tiền')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">Lịch sử Hoàn Tiền</h3>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Số Tiền Được Hoàn</th>
                        <th>Số Dư Trước</th>
                        <th>Số Dư Sau</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($refunds) && count($refunds) > 0)
                        @foreach($refunds as $refund)
                            <tr>
                                <td>{{ $refund->order_id }}</td>
                                <td>{{ intval($refund->refund_amount) == $refund->refund_amount ? number_format($refund->refund_amount, 0) : number_format($refund->refund_amount, 2) }} đ</td>
                                <td>{{ intval($refund->balance_before) == $refund->balance_before ? number_format($refund->balance_before, 0) : number_format($refund->balance_before, 2) }} đ</td>
                                <td>{{ intval($refund->balance_after) == $refund->balance_after ? number_format($refund->balance_after, 0) : number_format($refund->balance_after, 2) }} đ</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">Không có lịch sử hoàn tiền nào.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection