@extends('Admin.Layout.App')
@section('title', 'Chỉnh sửa thành viên')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Chỉnh sửa thành viên</h4>
                    <div class="mb-3">
                        <form action="{{ route('admin.user.edit.post', $user->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="name"
                                            value="{{ $user->name }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Họ và tên</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="email"
                                            value="{{ $user->email }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Email</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="username"
                                            value="{{ $user->username }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Tài khoản</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control border border-info" name="balance"
                                            value="{{ $user->balance }}" placeholder="Name">
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Số dư</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select name="level" id="" class="form-select border border-info">
                                            <option value="1" @if ($user->level == 5) selected @endif>Khách</option>
                                            <option value="1" @if ($user->level == 1) selected @endif>Thành viên</option>
                                            <option value="2" @if ($user->level == 2) selected @endif>Cộng tác viên</option>
                                            <option value="3" @if ($user->level == 3) selected @endif>Đại lý</option>
                                            <option value="4"@if ($user->level == 4) selected @endif>Nhà phân phối</option>
                                        </select>
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Cấp độ</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select name="status" id="" class="form-select border border-info">
                                            <option value="active" @if ($user->status == 'active') selected @endif>Hoạt động</option>
                                            <option value="banner" @if ($user->status != 'active') selected @endif>Bị khoá</option>
                                        </select>
                                        <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                                class="border-start border-info ps-3">Cấp độ</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary col-12">Lưu thông tin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Thay đổi mật khẩu</h4>
                    <div class="mb-3">
                        <form action="{{ route('admin.user.change-password.post', $user->id) }}" method="POST">
                            @csrf
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control border border-info" name="password" placeholder="Name">
                                <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Mật khẩu mới</span></label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control border border-info" name="password_confirm" placeholder="Name">
                                <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                        class="border-start border-info ps-3">Nhập lại mật khẩu</span></label>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary col-12">Thay đổi mật khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
