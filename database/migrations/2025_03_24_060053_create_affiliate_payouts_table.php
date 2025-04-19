<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('affiliate_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Người dùng
            $table->decimal('amount', 10, 2); // Số tiền thanh toán
            $table->date('date'); // Ngày thanh toán
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending'); // Trạng thái thanh toán
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_payouts');
    }
};
