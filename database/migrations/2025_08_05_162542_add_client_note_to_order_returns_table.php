<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('order_returns', function (Blueprint $table) {
            $table->text('client_note')->nullable()->after('admin_note');
        });
    }

    public function down()
    {
        Schema::table('order_returns', function (Blueprint $table) {
            $table->dropColumn('client_note');
        });
    }
};
