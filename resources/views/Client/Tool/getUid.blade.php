@extends('Layout.App')
@section('title', 'Get UID')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Get UID Facebook</h4>
                    <form action="{{ route('tool.uid.post', 'get-uid') }}" method="POST">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-info" placeholder="Link" name="link" id="link" value="{{ session('link') }}">
                            <label><i class="ti ti-coins me-2 fs-4 text-info"></i><span
                                    class="border-start border-info ps-3">Link Facebook</span></label>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary col-12" id="getUID">Get UID</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
