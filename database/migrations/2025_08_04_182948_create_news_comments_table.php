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
            $table->unsignedBigInteger('news_id');
            $table->foreign('news_id')->references('id')->on('news')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_hidden')->default(false);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('news_comments');
    }
}


