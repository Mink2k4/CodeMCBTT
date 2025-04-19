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
        Schema::create('server_services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('social_id')->nullable();
            $table->string('service_id')->nullable();
            $table->string('server')->nullable();
            $table->longText('price')->nullable();
            $table->longText('price_collaborator')->nullable();
            $table->longText('price_agency')->nullable();
            $table->longText('price_distributor')->nullable();
            $table->longText('min')->nullable();
            $table->longText('max')->nullable();
            $table->longText('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->nullable();
            $table->string('actual_service')->nullable();
            $table->string('actual_server')->nullable();
            $table->string('actual_path')->nullable();
            $table->string('actual_price')->nullable();
            $table->string('action', 999)->nullable();
            $table->string('order_type')->nullable();
            $table->string('warranty')->nullable();
            $table->string('service_list')->nullable();
            $table->string("id_lienket")->nullable();
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
        Schema::dropIfExists('server_services');
    }
};
