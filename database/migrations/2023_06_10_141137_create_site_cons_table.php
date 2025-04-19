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
        Schema::create('site_cons', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('domain_name')->unique();
            $table->string('status')->nullable();
            $table->string('zone_id')->nullable();
            $table->string('status_cloudflare')->nullable();
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
        Schema::dropIfExists('site_cons');
    }
};
