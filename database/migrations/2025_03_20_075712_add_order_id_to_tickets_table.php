<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('order_id')->nullable()->after('status'); // Thêm cột order_id sau cột status
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('order_id'); // Xóa cột order_id nếu rollback migration
        });
    }
};
