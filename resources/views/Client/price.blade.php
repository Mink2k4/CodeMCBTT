@extends('Layout.App')
@section('title', 'Bảng giá dịch vụ')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="accordion" id="accordionExample">
                @foreach ($services as $item)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $item->id }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseOne{{ $item->id }}" aria-expanded="false" aria-controls="collapseOne">
                                {{ $item->name }}
                            </button>
                        </h2>
                        <div id="collapseOne{{ $item->id }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $item->id }}" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                <div class="table-responsive overflow-x-auto w-100">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="1">Tên, máy chủ</th>
                                                <th colspan="6">Ghi chú</th>
                                                <th>Thành viên</th>
                                                <th>Cộng tác viên</th>
                                                <th>Đại lý</th>
                                                <th>Nhà phân phối</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @inject('server', '\App\Models\ServerService')
                                            @foreach ($server->getServerByService($item->id) as $server)
                                                <tr>
                                                    <td>{{ $server->title }} - Máy chủ {{ $server->server }}</td>
                                                    <td colspan="6">{{ $server->description }}</td>
                                                    <td><span class="badge bg-success">{{ $server->price }}</span></td>
                                                    <td><span
                                                            class="badge bg-primary">{{ $server->price_collaborator }}</span>
                                                    </td>
                                                    <td><span class="badge bg-warning">{{ $server->price_agency }}</span>
                                                    </td>
                                                    <td><span class="badge bg-info">**</span></td>
                                                    <td>{!! statusService($server->status) !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
