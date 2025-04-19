@extends('Admin.Layout.App')
@section('title', 'Chỉnh sửa số dư thành viên')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Thay đổi thành viên</h4>
                <div class="mb-3">
                    <form action="{{ route('admin.refund.balance.post') }}" method="POST">
                        @csrf
                        <!-- Thêm Order ID vào form -->
                        <input type="hidden" name="order_id" value="{{ $refund->order_id }}">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="username" value="{{ $user->username }}" readonly>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Tài khoản</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="hidden" name="action" value="plus">
                            <select class="form-select border border-info" disabled>
                                <option value="plus" selected>Cộng</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Thao tác</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control border border-info" name="balance" placeholder="Số tiền" required>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Số tiền</span></label>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary col-12">
                                Thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
