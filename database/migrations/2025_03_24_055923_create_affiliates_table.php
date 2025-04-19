<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // Người dùng
            $table->integer('visits')->default(0); // Lượt truy cập
            $table->integer('registrations')->default(0); // Đăng ký
            $table->integer('referrals')->default(0); // Giới thiệu thành công
            $table->decimal('conversion_rate', 5, 2)->default(0.00); // Tỷ lệ chuyển đổi
            $table->decimal('total_earnings', 10, 2)->default(0.00); // Tổng thu nhập
            $table->decimal('available_earnings', 10, 2)->default(0.00); // Thu nhập khả dụng
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliates');
    }
};
