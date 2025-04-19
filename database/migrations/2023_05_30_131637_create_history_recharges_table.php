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
        Schema::create('history_recharges', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('name_bank')->nullable();
            $table->string('type_bank')->nullable();
            $table->longText('tranid')->nullable();
            $table->longText('amount')->nullable();
            $table->longText('promotion')->nullable();
            $table->longText('real_amount')->nullable();
            $table->string('status')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('history_recharges');
    }
};
