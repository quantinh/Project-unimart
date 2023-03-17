<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    protected $fillable=[
        'title',
        'slug',
        'content',
        'description',
        'thumbnail',
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
