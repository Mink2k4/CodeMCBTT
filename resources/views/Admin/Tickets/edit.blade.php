@extends('Admin.Layout.App')

@section('content')
<div class="container">
    <h2>Chỉnh sửa nội dung Ticket</h2>
    <form action="{{ route('admin.tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="description">Nội dung:</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ $ticket->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.tickets.detail', $ticket->id) }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
