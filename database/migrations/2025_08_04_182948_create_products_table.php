<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['simple', 'variable'])->default('simple');
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
