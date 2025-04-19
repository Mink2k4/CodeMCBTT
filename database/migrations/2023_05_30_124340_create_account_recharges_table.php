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
        Schema::create('account_recharges', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account')->nullable();
            $table->string('account_number')->nullable();
            $table->string('password')->nullable();
            $table->string('api_token')->nullable();
            $table->longText('qr_code')->nullable();
            $table->longText('quy_doi')->nullable();            
            $table->longText('logo')->nullable();
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
        Schema::dropIfExists('account_recharges');
    }
};
