<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostCat extends Model
{
    //Dùng phần này để có thể sử dụng phương thức withTrash theo eloquent ORM
    use SoftDeletes;
    //Được hiểu model này có tương tác với table catposts trên csdl
    protected $fillable = [
        'cat_name',
        'slug',
        'status',
        'parent_id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    function posts()
    {
        return $this->hasMany('App\Post');
    }

    //Phương thức định nghĩa liên kết mối liên hệ n:1 với model User (Tất cả bài viết này từ user_id nào tạo)
    function User()
    {
        return $this->belongsTo('App\User');
    }
}
