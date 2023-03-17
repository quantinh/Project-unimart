<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    // protected $table = ['tên bảng để lỡ ghi sai tên bảng ghi thì ko bị lỗi'];
    protected $fillable = [
        'image',
        'status',
        'position',
        'user_id',
        'created_at',
        'updated_at'
    ];
    //Phương thức định nghĩa liên kết mối liên hệ n:1 với model User (Tất cả bài viết này từ user_id nào tạo)
    function User()
    {
        return $this->belongsTo('App\User');
    }
}
