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
        Schema::create('data_histories', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('action')->nullable();
            $table->longText('data')->nullable();
            $table->longText('old_data')->nullable();
            $table->longText('new_data')->nullable();
            $table->string('ip')->nullable();
            $table->longText('description')->nullable();
            $table->longText('data_json')->nullable();
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
        Schema::dropIfExists('data_histories');
    }
};
