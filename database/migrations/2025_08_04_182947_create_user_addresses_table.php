
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id(); // Khóa chính mặc định là cột id
            $table->unsignedBigInteger('user_id'); // Khóa ngoại liên kết với bảng users
            $table->string('address_line'); // Địa chỉ chi tiết
            $table->string('ward')->nullable(); // Phường
            $table->string('district')->nullable(); // Quận
            $table->string('city')->nullable(); // Thành phố
            $table->boolean('is_default')->default(false); // Địa chỉ mặc định
            $table->timestamps(); 
            $table->softDeletes(); 
            // Ràng buộc khóa ngoại
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};


