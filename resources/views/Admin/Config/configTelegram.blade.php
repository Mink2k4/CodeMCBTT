@extends('Admin.Layout.App')
@section('title', 'Cấu hình telegram')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="h4 card-title">Cấu hình</div>
                    <form action="{{ route('admin.config.telegram.post') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="url" class="form-control border border-info" name="telegram_bot"
                                value="{{ DataSite('telegram_bot') }}" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Link telegram Bot</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="telegram_token"
                                value="{{ DataSite('telegram_token') }}" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Telegram Token</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="telegram_chat_id"
                                value="{{ DataSite('telegram_chat_id') }}" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Telegram Chat Id (Lấy chat ID Của tài khoản bạn không phải bot)</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="balance_telegram"
                                value="{{ DataSite('balance_telegram') }}" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Số tiền được nhận khi liên kết telegram</span></label>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary col-12">
                                Lưu cấu hình
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
