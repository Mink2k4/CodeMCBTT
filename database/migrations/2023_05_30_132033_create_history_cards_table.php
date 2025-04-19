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
        Schema::create('history_cards', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('card_type')->nullable();
            $table->string('card_amount')->nullable();
            $table->string('card_code')->nullable();
            $table->string('card_serial')->nullable();
            $table->string('card_real_amount')->nullable();
            $table->string('status')->nullable();
            $table->string('note')->nullable();
            $table->string('tranid')->nullable();
            $table->string('promotion')->nullable();
            $table->string('discount')->nullable();
            $table->timestamps();
            $table->string('domain')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_cards');
    }
};
