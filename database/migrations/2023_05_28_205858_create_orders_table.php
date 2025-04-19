<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('service_id')->nullable();
            $table->string('service_name')->nullable();
            $table->string('server_service')->nullable();
            $table->longText('price')->nullable();
            $table->longText('quantity')->nullable();
            $table->longText('total_payment')->nullable();
            $table->longText('order_code')->nullable();
            $table->longText('order_link')->nullable();
            $table->longText('start')->nullable();
            $table->longText('buff')->nullable();
            $table->longText('actual_service')->nullable();
            $table->longText('actual_path')->nullable();
            $table->longText('actual_server')->nullable();
            $table->longText('status')->nullable();
            $table->longText('action')->nullable();
            $table->longText('dataJson')->nullable();
            $table->string('error')->nullable();
            $table->longText('isShow')->nullable();
            $table->longText('history')->nullable();
            $table->longText('refund')->nullable();
            $table->longText('note')->nullable();
            $table->timestamps();
            $table->string('domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
