@extends('Admin.Layout.App')
@section('title', 'Danh sách máy chủ dịch vụ')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="flex-shrink-0">
                            <h4 class="card-title">Danh sách dịch vụ</h4>
                        </div>
                        <div class="">
                            <a href="javascript:;" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal"
                                data-bs-target="#bs-example-modal-md">
                                <i class="fas fa-plus"></i> Thông báo cho telegram
                            </a>
                            <a href="javascript:;" class="btn btn-success btn-sm float-right" data-bs-toggle="modal"
                            data-bs-target="#bs-example-modal-md2">
                                <i class="fas fa-plus"></i> Thêm dịch vụ tự động
                            </a>
                            <a href="{{ route('admin.server.delete-all') }}" class="btn btn-danger btn-sm">Xoá toàn bộ dịch vụ</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div id="" class="dataTables_wrapper">
                            <table id="history"
                                class="table border table-striped table-bordered display text-nowrap dataTable responsive w-100"
                                aria-describedby="file_export_info">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên dịch vụ</th>
                                        <th>Dịch vụ</th>
                                        <th>Máy chủ</th>
                                        <th>Giá</th>
                                        <th>Giá cộng tác viên</th>
                                        <th>Giá đại lý</th>
                                        <th>Giá nhà phân phối</th>
                                        <th>Giá gốc</th>
                                        <th>Tối thiểu</th>
                                        <th>Tối đa</th>
                                        <th>Tiêu đề</th>
                                        <th>Trạng thái</th>
                                        <th>Thời gian tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="bs-example-modal-md" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myModalLabel">
                        Thông báo dịch vụ cho telegram
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.server.notification-telegram.post') }}" method="POST" request="lbd">
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <select name="social" id="" class="form-select border border-info">
                                <option value="">Chọn dịch vụ</option>
                                @foreach ($social as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">MXH</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="service" id="" class="form-select border border-info">
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Máy chủ</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea name="content" id="" cols="30" rows="5" class="form-control border border-primary"></textarea>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Nội dung cần thông báo</span></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-light-primary text-primary font-medium waves-effect">
                            Gửi thông báo
                        </button>
                        <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect"
                            data-bs-dismiss="modal">
                            Đóng
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div id="bs-example-modal-md2" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myModalLabel">
                        Thao tác
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.server.auto-create') }}" method="POST" request="lbd">
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <select name="action" id="" class="form-select border border-info">
                                <option value="update">Cập nhật lại các dịch vụ đã thêm</option>
                                <option value="add">Thêm các dịch vụ mới nếu có</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Thao tác</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="type" id="" class="form-select border border-info">
                                <option value="default">Tự thay đổi giá tiền</option>
                                <option value="percent">Giá tiền tự thay đổi theo %</option>
                                <option value="add">Tự cộng thêm số tiền giá nhập ở bên giới</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Thao tác khi thêm</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="price" id="" cols="30" rows="5" class="form-control border border-primary">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Nhập giá tiền</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="price_collaborator" id="" cols="30" rows="5" class="form-control border border-primary">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Nhập giá cộng tác viên</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="price_agency" id="" cols="30" rows="5" class="form-control border border-primary">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Nhập giá đại lý</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input name="price_distributor" id="" cols="30" rows="5" class="form-control border border-primary">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Nhập giá nhà phân phối</span></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-light-primary text-primary font-medium waves-effect">
                            Thêm tự động máy chủ
                        </button>
                        <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect"
                            data-bs-dismiss="modal">
                            Đóng
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('select[name=social]').change(function() {
                $.ajax({
                    url: "{{ route('admin.service.checking.post') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: $(this).val()
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 'success') {
                            var service = $('select[name=service]');
                            service.empty();
                            $.each(data.data, function(key, value) {
                                service.append('<option value="' + value.id + '">' +
                                    value.name + '</option>');
                            })
                        }
                    },
                    error: function(data) {
                        if (data.status == 500) {
                            toastr.error(data.responseJSON.message);
                        }
                    }
                })
            })
        });
    </script>
    <script>
        createDataTable('#history', '{{ route('admin.list', 'list-server') }}', [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'service',
                name: 'service'
            },
            {
                data: 'server',
                name: 'server'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'price_collaborator',
                name: 'price_collaborator'
            },
            {
                data: 'price_agency',
                name: 'price_agency'
            },
            {
                data: 'price_distributor',
                name: 'price_distributor'
            },
            {
                data: 'actual_price',
                name: 'actual_price'
            },
            {
                data: 'min',
                name: 'min'
            },
            {
                data: 'max',
                name: 'max'
            },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    return data == 'Active' ? `<span class="badge bg-success">Hoạt động</span>` :
                        `<span class="badge bg-danger">Không hoạt động</span>`
                }
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: null,
                render: function(data, type, row) {
                    return `<a href="{{ route('admin.server.edit', 'id') }}" class="btn btn-sm btn-primary"><i class="ti ti-eye"></i></a>
                            <a href="{{ route('admin.server.delete', 'id') }}" class="btn btn-sm btn-danger"><i class="ti ti-trash"></i></a>`
                        .replace(/id/g, data.id)
                }
            },
        ])
    </script>
@endsection
