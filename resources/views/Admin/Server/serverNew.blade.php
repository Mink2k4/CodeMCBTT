@extends('Admin.Layout.App')
@section('title', 'Thêm máy chủ mới')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Thêm dịch vụ mới</h4>
                <div class="mb-3">
                    <form action="{{ route('admin.server.new.post') }}" method="POST" request="lbd">
                        <div class="form-floating mb-3">
                            <select name="social" id="social" class="form-select border border-info">
                                <option value="">Chọn dịch vụ MXH</option>
                                @foreach ($social as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Dịch vụ MXH</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="service" id="serviceMXH" class="form-select border border-info">
                                <option value="">Vui lòng chọn dịch vụ MXH</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Dịch vụ </span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="server_service" id="" class="form-select border border-info">
                                @for ($i = 1; $i < 100; $i++) <option value="{{ $i }}">Server: {{ $i }}</option>
                                    @endfor
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Máy chủ </span></label>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-info" name="price" placeholder="Name">
                                    <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Giá</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-info" name="price_collaborator" placeholder="Name">
                                    <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Giá Collaborator</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-info" name="price_agency" placeholder="Name">
                                    <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Giá Agency</span></label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-info" name="price_distributor" placeholder="Name">
                                    <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Giá Distributor</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-info" name="min" placeholder="Name">
                                    <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Mua tối thiểu</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-info" name="max" placeholder="Name">
                                    <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Mua tối đa</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="title" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Tiêu đề</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea type="text" class="form-control border border-info" name="description" rows="5" placeholder="Name"></textarea>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Nội dung</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <select class="form-control border border-info" name="actual_service" placeholder="Name">
                                <option value="subgiare">Subgiare.com</option>
                                <option value="hacklike17">Hacklike17.com</option>
                                <option value="2mxh">2mxh.com</option>
                                <option value="1dg">1dg.me</option>
                                <option value="TDS">TDS</option>
                                <option value="dontay">Đơn tay</option>
                                <option value="flare">flare</option>
                                <option value="jap">jap</option>
                                <option value="tuongtaccheo">Tương tác chéo</option>
                                <option value="trumvip">Trumvip</option>
                                <option value="dino">dino</option>
                                <option value="Smm(Quantity/2.2)">Smm(Quantity/2.2)</option>
                                <option value="Smm(Quantity/1.5)">Smm(Quantity/1.5)</option>
                                <option value="Smm(Quantity-10%)">Smm(Quantity-10%)</option>
                                <option value="Smm(Quantity-8%)">Smm(Quantity-8%)</option>
                                <option value="Smm(Quantity-2%)">Smm(Quantity-2%)</option>
                                <option value="Smm(Quantity-0%)">Smm(Quantity-0%)</option>
                                <option value="n1panel">N1panel</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Nguồn</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="service_list" placeholder="Nhập ID service">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">ID service</span></label>
                        </div>
                        <div class="form-floating mb-3" id="service" style="display: none;">
                            <select class="form-control border border-info" id="service_list" name="service_list" placeholder="Service">

                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Service</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="actual_server" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Máy chủ nguồn</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" name="actual_path" placeholder="Name">
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Đường dẫn nguồn</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <select type="text" class="form-select border border-info" name="action">
                                <option value="default">Mặc định</option>
                                <option value="get-uid">Tự động get UID</option>
                                <option value="get-username-tiktok">Tự động get username-tiktok</option>
                                <option value="get-order">Tự động get Số lượng đơn (Chỉ Hacklike17)</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Thao tác khi chọn server</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <select type="text" class="form-select border border-info" name="order_type">
                                <option value="default">Không hoàn tiền</option>
                                <option value="refund">Được hoàn tiền</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Khi tạo đơn hàng</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <select type="text" class="form-select border border-info" name="warranty">
                                <option value="no">Không bảo hành</option>
                                <option value="yes">Được bảo hành</option>
                            </select>
                            <label><i class="ti ti-topology-star-ring-3 me-2 fs-4 text-info"></i><span class="border-start border-info ps-3">Bảo hành</span></label>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary col-12">Thêm máy chủ mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    let dataService = [];
    var selectedValue;
    $(document).ready(function() {
        const socialSelect = document.getElementById('serviceMXH');

        // Add an event listener to the <select> element
        socialSelect.addEventListener('change', function() {
            // Get the selected value
            selectedValue = socialSelect.value;

        });
        $('select[name="actual_service"]').change(function() {
            // Check the selected option's value
            if ($(this).val() === 'smmflare') {
                $('#service').show();
                $.ajax({
                    type: 'GET',
                    url: '/api/getService',
                    data: {
                        id: selectedValue,
                        source: "smmflare"
                    },
                    success: function(data) {
                        dataService.push(data); // Push the entire data object
                        // Assuming data is an array of objects with 'service' and 'name' properties
                        var select = $('#service_list');
                        select.empty();

                        // Populate the select with options based on the API response
                        data.categories.forEach(function(item) {
                            select.append($('<option>', {
                                value: item.service,
                                text: item.name
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                    }
                });
            }
            else if($(this).val() === 'secsers'){
                $('#service').show();
                $.ajax({
                    type: 'GET',
                    url: '/api/getService',
                    data: {
                        id: selectedValue,
                        source: "secsers"
                    },
                    success: function(data) {
                        dataService.push(data); // Push the entire data object
                        // Assuming data is an array of objects with 'service' and 'name' properties
                        var select = $('#service_list');
                        select.empty();

                        // Populate the select with options based on the API response
                        data.categories.forEach(function(item) {
                            select.append($('<option>', {
                                value: item.service,
                                text: item.name
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                    }
                });
            }
            else if($(this).val() === 'justanotherpanel'){
                $('#service').show();
                $.ajax({
                    type: 'GET',
                    url: '/api/getService',
                    data: {
                        id: selectedValue,
                        source: "justanotherpanel"
                    },
                    success: function(data) {
                        dataService.push(data); // Push the entire data object
                        // Assuming data is an array of objects with 'service' and 'name' properties
                        var select = $('#service_list');
                        select.empty();

                        // Populate the select with options based on the API response
                        data.categories.forEach(function(item) {
                            select.append($('<option>', {
                                value: item.service,
                                text: item.name
                            }));
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors
                    }
                });
            }
             else {
                $('#service').hide();
            }
        });
        $('#service_list').change(function() {
            var selectedValue = $(this).val();
            var selectedText = $(this).find(':selected').text();
            $('input[name="title"]').val(selectedText);

            var selectedItem = dataService[0].categories.find(item => item.service == selectedValue);

            if (selectedItem) {
                $('input[name="min"]').val(selectedItem.min);
                $('input[name="max"]').val(selectedItem.max);
            }
        });
        // Change social value
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
                        service.append('<option value="">Vui lòng chọn dịch vụ MXH</option>' )
                        $.each(data.data, function(key, value) {
                            service.append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                },
                error: function(data) {
                    if (data.status == 500) {
                        toastr.error(data.responseJSON.message);
                    }
                }
            });
        });
    });
</script>
@endsection