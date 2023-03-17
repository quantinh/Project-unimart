<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cats', function (Blueprint $table) {
            $table->id();
            $table->string('cat_name', 255);
            $table->string('slug', 100);
            $table->enum('status', ['Chờ duyệt', 'Công khai']);
            //Tạo trường user_id với số nguyên lớn
            $table->unsignedBigInteger('user_id');
            //Tạo khóa ngoại user_id trỏ đến khóa chính id của bảng users để biết được người nào đã tạo danh mục này hiển thị ở view
            $table->foreign('user_id')->references('id')->on('users');
            //Tạo trường mới softDelete
            $table->softDeletes();
            //Tự động tạo hai trường created_ad và updated_at
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
        Schema::dropIfExists('product_cats');
    }
}
