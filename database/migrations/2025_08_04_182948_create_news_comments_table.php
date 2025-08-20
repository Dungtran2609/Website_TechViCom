<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('news_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('news_id')->constrained('news')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('news_comments')->onDelete('cascade'); // 💬 reply
            $table->text('content');
            $table->boolean('is_hidden')->default(false);
            $table->unsignedInteger('likes_count')->default(0); // 👍 optional
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('news_comments');
    }
}
