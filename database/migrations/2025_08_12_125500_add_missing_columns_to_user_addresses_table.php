<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('user_addresses', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('user_addresses', 'phone')) {
                $table->string('phone')->nullable()->after('recipient_name');
            }
            if (!Schema::hasColumn('user_addresses', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            if (Schema::hasColumn('user_addresses', 'recipient_name')) {
                $table->dropColumn('recipient_name');
            }
            if (Schema::hasColumn('user_addresses', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('user_addresses', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
