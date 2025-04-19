@extends('Layout.App')

@section('content')
<div class="container">
    <h2>Tạo Ticket Hỗ Trợ</h2>

    <form action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Thể Loại -->
        <div class="mb-3">
            <label for="category" class="form-label">Thể loại:</label>
            <select id="category" name="category" class="form-control" required>
                <option value="">Chọn thể loại</option>
                <option value="Hỗ trợ order">Hỗ trợ order</option>
                <option value="Hỗ trợ nạp tiền">Hỗ trợ nạp tiền</option>
            </select>
        </div>

        <!-- Loại -->
        <div class="mb-3">
            <label for="type" class="form-label">Loại:</label>
            <select id="type" name="type" class="form-control" required>
                <option value="">Chọn loại</option>
            </select>
        </div>

        <!-- Order ID -->
        <div class="mb-3">
            <label for="order_id" class="form-label">Order ID:</label>
            <input type="number" id="order_id" name="order_id" class="form-control" required min="1">
        </div>

        <!-- Lý do -->
        <div class="mb-3">
            <label for="reason" class="form-label">Lý do:</label>
            <textarea id="reason" name="reason" class="form-control" rows="3" required></textarea>
        </div>

        <!-- Upload Ảnh -->
        <div class="mb-3">
            <label for="image" class="form-label">Tải ảnh lên:</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>

        <!-- Nút Gửi -->
        <button type="submit" class="btn btn-primary">Gửi Đơn</button>
    </form>
</div>

<script>
    document.getElementById("category").addEventListener("change", function() {
        var typeSelect = document.getElementById("type");
        typeSelect.innerHTML = ""; // Xóa tất cả option cũ

        if (this.value === "Hỗ trợ order") {
            var options = ["Hoàn tiền", "Bảo Hành", "Tăng Tốc", "Khác"];
        } else if (this.value === "Hỗ trợ nạp tiền") {
            var options = ["Nạp tiền chưa cộng", "Khác"];
        } else {
            var options = [];
        }

        options.forEach(function(option) {
            var newOption = document.createElement("option");
            newOption.value = option;
            newOption.text = option;
            typeSelect.appendChild(newOption);
        });
    });
</script>

@endsection
