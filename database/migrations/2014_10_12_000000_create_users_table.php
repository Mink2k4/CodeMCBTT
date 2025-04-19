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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->longText('balance');
            $table->longText('total_recharge');
            $table->longText('total_deduct');
            $table->string('referral_link')->nullable();
            $table->longText('referral_money');
            $table->string('level')->default(5);
            $table->string('position')->default('member');
            $table->string('status')->default('active');
            $table->string('ip')->nullable();
            $table->string('api_token', 999)->nullable();
            $table->longText('facebook')->nullable();
            $table->string('avatar', 999)->nullable()->default('/dist/images/profile/user-1.jpg');
            $table->string('email_verified')->default('no')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('telegram_chat_id')->nullable();
            $table->string('telegram_verified')->default('no')->nullable();
            $table->string('telegram_notice')->default('no')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('domain')->default(env('PARENT_SITE'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
