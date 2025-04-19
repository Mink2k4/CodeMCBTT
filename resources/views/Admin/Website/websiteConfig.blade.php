@extends('Admin.Layout.App')
@section('title', 'Cấu hình website')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Cấu hình chung</h4>
                    <div class="mb-3">
                        <form action="{{ route('admin.website.config.post') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="namesite"
                                            value="{{ DataSite('namesite') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Tên site</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="title"
                                            value="{{ DataSite('title') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Tiêu đề</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="description"
                                            value="{{ DataSite('description') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mô tả</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="keyword"
                                            value="{{ DataSite('keyword') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Từ khoá</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="image_seo"
                                            value="{{ DataSite('image_seo') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Image SEO</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="collaborator"
                                            value="{{ DataSite('collaborator') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mức nạp Cộng tác viên</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="agency"
                                            value="{{ DataSite('agency') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mức nạp Đại lý</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="distributor"
                                            value="{{ DataSite('distributor') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mức nạp Nhà phân phối</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="code_tranfer"
                                            value="{{ DataSite('code_tranfer') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mã chuyển khoản</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info"
                                            name="recharge_promotion" value="{{ DataSite('recharge_promotion') }}"
                                            placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Khuyến mãi</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="facebook"
                                            value="{{ DataSite('facebook') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Facebok Admin</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="zalo"
                                            value="{{ DataSite('zalo') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Zalo Admin</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="telegram"
                                            value="{{ DataSite('telegram') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Telegram Admin</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="youtube"
                                            value="{{ DataSite('youtube') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Youtube (Nếu có)</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="email"
                                            value="{{ DataSite('email') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Email Admin</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="phone"
                                            value="{{ DataSite('phone') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Phone Admin</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="partner_id"
                                            value="{{ DataSite('partner_id') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Partner ID</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="partner_key"
                                            value="{{ DataSite('partner_key') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Partner Key</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="card_discount"
                                            value="{{ DataSite('card_discount') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Chiết khấu thẻ</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="mail_host"
                                            value="{{ DataSite('mail_host') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mail Host</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="mail_port"
                                            value="{{ DataSite('mail_port') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mail Port</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info"
                                            name="mail_username" value="{{ DataSite('mail_username') }}"
                                            placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mail (Địa chỉ email)</span></label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating mb-3">
                                        <input type="password" class="form-control border border-info"
                                            name="mail_password" value="{{ DataSite('mail_password') }}"
                                            placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Mail Password</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control border border-info" name="script_header" placeholder="Leave a comment here"
                                            id="floatingTextarea2" style="height: 100px">{{ DataSite('script_header') }}</textarea>
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Script Header</span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control border border-info" name="script_footer" placeholder="Leave a comment here"
                                            id="floatingTextarea2" style="height: 100px">{{ DataSite('script_footer') }}</textarea>
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Script Footer</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info"
                                            name="google_client_id" value="{{ DataSite('google_client_id') }}"
                                            placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Google Client ID</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info"
                                            name="google_client_secret" value="{{ DataSite('google_client_secret') }}"
                                            placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Google Client Secret</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info"
                                            name="google_redirect" value="{{ DataSite('google_redirect') }}"
                                            placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Google Redirect</span></label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info"
                                            name="facebook_client_id" value="{{ DataSite('facebook_client_id') }}"
                                            placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Facebook Client ID</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info"
                                            name="facebook_client_secret"
                                            value="{{ DataSite('facebook_client_secret') }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Facebook Client Secret</span></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info"
                                            name="facebook_redirect" value="{{ DataSite('facebook_redirect') }}"
                                            placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Facebook Redirect</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="form-group">
                                        <input class="form-check-input" type="checkbox" name="notice_order"
                                            id="notice_order" {{ DataSite('notice_order') == 'on' ? 'checked' : '' }}>
                                        <label for="notice_order" class="form-label">Thông báo đơn về telegram</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="form-group">
                                        <input class="form-check-input" type="checkbox" name="notice_login"
                                            id="notice_login" {{ DataSite('notice_login') == 'on' ? 'checked' : '' }}>
                                        <label for="notice_login" class="form-label">Cảnh báo đăng nhập</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary col-12">Lưu cấu hình</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
