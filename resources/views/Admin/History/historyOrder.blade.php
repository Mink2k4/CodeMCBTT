@extends('Admin.Layout.App')
@section('title', 'Lịch sử đơn hàng')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lịch sử tạo đơn</h4>
                    <div class="table-responsive">
                        <div id="" class="dataTables_wrapper w-100 overflow-x-auto overflow-y-hidden">
                            <table id="testds"
                                class="table border table-striped table-bordered display text-nowrap dataTable responsive w-100"
                                aria-describedby="file_export_info">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tài khoản</th>
                                        <th>Dịch vụ</th>
                                        <th>Máy chủ</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tiền</th>
                                        <th>Đơn hàng</th>
                                        <th>Bắt đầu</th>
                                        <th>Đã tăng</th>
                                        <th>Nguồn</th>
                                        <th>Đường dẫn nguồn</th>
                                        <th>Máy chủ nguồn</th>
                                        <th>Trạng thái</th>
                                        <th>Ghi chú</th>
                                        <th>Thời gian</th>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lịch sử đơn tay</h4>
                    <button id="approveAllOrders" class="btn btn-primary mb-3" style="background-color: #5c8afe; border-color: #5c8afe; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                        <i class="fas fa-check-circle"></i> Duyệt Tất Cả Đơn
                    </button>
                    <div class="table-responsive">
                        <div id="" class="dataTables_wrapper w-100 overflow-x-auto overflow-y-hidden">
                            <table id="dontay"
                                class="table border table-striped table-bordered display text-nowrap dataTable responsive w-100"
                                aria-describedby="file_export_info">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Thao tác</th>
                                        <th>Tài khoản</th>
                                        <th>Dịch vụ</th>
                                        <th>Máy chủ</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tổng tiền</th>
                                        <th>Đơn hàng</th>
                                        <th>Bắt đầu</th>
                                        <th>Đã tăng</th>
                                        <th>Nguồn</th>
                                        <th>Đường dẫn nguồn</th>
                                        <th>Máy chủ nguồn</th>
                                        <th>Trạng thái</th>
                                        <th>Ghi chú</th>
                                        <th>Thời gian</th>
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
@endsection
@section('script')
    <script>
        createDataTable('#testds', '{{ route('admin.list', 'list-order') }}', [{
            data: 'id'
        }, {
            data: 'username'
        }, {
            data: 'service_name',
            render: function(data, type, row) {
                return `<span class="badge badge-pill bg-primary">${data}</span>`
            }
        }, {
            data: 'server_service',
            render: function(data, type, row) {
                return `<span class="badge badge-pill bg-warning">${data}</span>`
            }
        }, {
            data: 'price',
            render: function(data, type, row) {
                return `<b class="text-primary">${data}</b>`
            }
        }, {
            data: 'quantity',
            render: function(data, type, row) {
                return `<b class="text-info">${formatNumber(data)}</b>`
            }
        }, {
            data: 'total_payment',
            render: function(data, type, row) {
                return `<b class="text-success">${formatNumber(data)}</b>`
            }
        }, {
            data: 'order_link'
        }, {
            data: 'start',
            render: function(data, type, row) {
                return `<b class="text-seconadry">${formatNumber(data)}</b>`
            }
        }, {
            data: 'buff',
            render: function(data, type, row) {
                return `<b class="text-success">${formatNumber(data)}</b>`
            }
        }, {
            data: 'actual_service',
            visible: !window.location.href.includes('meoconbantuongtac'),
        }, {
            data: 'actual_path',
            visible: !window.location.href.includes('meoconbantuongtac'),
        }, {
            data: 'actual_server',
            visible: !window.location.href.includes('meoconbantuongtac'),
        }, {
            data: 'status_order'
        }, {
            data: 'note'
        }, {
            data: 'created_at'
        }, {
            data: null,
            render: function(data, type, row) {
                return ''
            }
        }])
    </script>
        <script>
            createDataTable('#dontay', '{{ route('admin.list', 'order-tay') }}', [{
                    data: 'id'
                }, {
                    data: null,
                    render: function(data, type, row) {
                        return `
                <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Thao tác
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="activeOrder('${row.id}')">Duyệt đơn</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="cancelOrder('${row.id}')">Hủy đơn</a></li>
                            </ul>
                        </div>
                `
                    }
                },
                {
                    data: 'username'
                }, {
                    data: 'service_name',
                    render: function(data, type, row) {
                        return `<span class="badge badge-pill bg-primary">${data}</span>`
                    }
                }, {
                    data: 'server_service',
                    render: function(data, type, row) {
                        return `<span class="badge badge-pill bg-warning">${data}</span>`
                    }
                }, {
                    data: 'price',
                    render: function(data, type, row) {
                        return `<b class="text-primary">${data}</b>`
                    }
                }, {
                    data: 'quantity',
                    render: function(data, type, row) {
                        return `<b class="text-info">${formatNumber(data)}</b>`
                    }
                }, {
                    data: 'total_payment',
                    render: function(data, type, row) {
                        return `<b class="text-success">${formatNumber(data)}</b>`
                    }
                }, {
                    data: 'order_link'
                }, {
                    data: 'start',
                    render: function(data, type, row) {
                        return `<b class="text-seconadry">${formatNumber(data)}</b>`
                    }
                }, {
                    data: 'buff',
                    render: function(data, type, row) {
                        return `<b class="text-success">${formatNumber(data)}</b>`
                    }
                }, {
                    data: 'actual_service'
                }, {
                    data: 'actual_path'
                }, {
                    data: 'actual_server'
                }, {
                    data: 'status_order'
                }, {
                    data: 'note'
                }, {
                    data: 'created_at'
                }, {
                    data: null,
                    render: function(data, type, row) {
                        return ''
                    }
                }
            ])
            $('#approveAllOrders').click(function() {
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn duyệt tất cả đơn?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: `Duyệt tất cả`,
                    denyButtonText: `Hủy`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/order/active-all',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'JSON',
                            success: function(data) {
                                if (data.status == 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: data.message,
                                    })
                                    $('#testds').DataTable().ajax.reload()
                                    $('#dontay').DataTable().ajax.reload()
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: data.message,
                                    })
                                }
                            }
                        })
                    }
                })
            });
            function activeOrder(id) {
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn duyệt đơn này?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: `Duyệt`,
                    denyButtonText: `Huỷ`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/order/active',
                            method: 'POST',
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'JSON',
                            success: function(data) {
                                if (data.status == 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: data.message,
                                    })
                                    $('#testds').DataTable().ajax.reload()
                                    $('#dontay').DataTable().ajax.reload()
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: data.message,
                                    })
                                }
                            }
                        })
                    }
                })
            }

            function cancelOrder(id) {
                Swal.fire({
                    title: 'Bạn có chắc chắn muốn hủy đơn này?',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: `Hủy`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/order/cancel',
                            method: 'POST',
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'JSON',
                            success: function(data) {
                                if (data.status == 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: data.message,
                                    })
                                    $('#testds').DataTable().ajax.reload()
                                    $('#dontay').DataTable().ajax.reload()
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: data.message,
                                    })
                                }
                            }
                        })
                    }
                })
            }
        </script>
    
@endsection
