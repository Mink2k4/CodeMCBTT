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
        Schema::table('refunds', function (Blueprint $table) {
            $table->decimal('balance_before', 15, 2)->after('refund_amount')->default(0);
            $table->decimal('balance_after', 15, 2)->after('balance_before')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn(['balance_before', 'balance_after']);
        });
    }
};
