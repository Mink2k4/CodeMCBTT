@extends('Admin.Layout.App')
@section('title', 'Chỉnh sửa dịch vụ MXH')

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sửa dịch vụ Social</h4>
                    <form action="{{ route('admin.service.social.edit.post', $social->id) }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-primary" placeholder="Tên MXH"
                                value="{{ $social->name }}" name="social_service">
                            <label for="">
                                <i class="ti ti-user fs-4 text-primary"></i>
                                <span class="border-start border-primary ps-2">
                                    Tên dịch vụ MXH
                                </span>
                            </label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-primary" placeholder="Tên MXH"
                                value="{{ $social->slug }}" name="social_path">
                            <label for="">
                                <i class="ti ti-link fs-4 text-primary"></i>
                                <span class="border-start border-primary ps-2">
                                    Path Dịch vụ (VD: sg-facebook)
                                </span>
                            </label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-primary" placeholder="Tên MXH"
                                value="{{ $social->image }}" name="social_image">
                            <label for="">
                                <i class="ti ti-photo-check fs-4 text-primary"></i>
                                <span class="border-start border-primary ps-2">
                                    Link Ảnh Dịch vụ
                                </span>
                            </label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="social_status" id="" class="form-select border border-primary">
                                <option value="show" @if ($social->status == 'show') selected @endif>Hiển thị</option>
                                <option value="hide" @if ($social->status == 'hide') selected @endif>Ẩn</option>
                            </select>
                            <label for="">
                                <i class="ti ti-status-change fs-4 text-primary"></i>
                                <span class="border-start border-primary ps-2">
                                    Trạng thái
                                </span>
                            </label>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary col-12">
                                Chỉnh sửa dịch vụ MXH
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
