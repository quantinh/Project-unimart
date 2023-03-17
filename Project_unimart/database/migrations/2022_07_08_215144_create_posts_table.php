<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('content');
            $table->string('thumbnail', 255);
            // Tạo trường user_id kiểu không dấu
            $table->unsignedBigInteger('user_id');
            // Tạo khóa ngoại user_id liến kết tới khóa chính id trong bảng users xóa dữ liệu cả 2 bảng
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Tạo trường user_id kiểu không dấu
            $table->unsignedBigInteger('cat_id');
            // Tạo khóa ngoại cat_id liến kết tới khóa chính id trong bảng cat_posts xóa dữ liệu cả 2 bảng
            $table->foreign('cat_id')->references('id')->on('post_cats')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
