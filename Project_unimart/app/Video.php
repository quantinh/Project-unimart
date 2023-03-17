<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //Dùng phần này để có thể sử dụng phương thức withTrash theo eloquent ORM
    use SoftDeletes;
    //Được hiểu model này có tương tác với table catposts trên csdl
    protected $fillable = [
        'name_video',
        'link',
        'status',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //Phương thức định nghĩa liên kết mối liên hệ n:1 với model User (Tất cả bài viết này từ user_id nào tạo)
    function User()
    {
        return $this->belongsTo('App\User');
    }
}
