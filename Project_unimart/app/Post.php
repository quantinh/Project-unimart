<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    //Được hiểu model này có tương tác với table posts trên csdl (nếu trường status ko đk khai báo thì sẽ không gửi lên được radio thì sẽ bị gán mặt định là công khai)
    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'status',
        'user_id',
        'cat_id',
        'created_at',
        'updated_at'
    ];

    //Phương thức định nghĩa liên kết mối liên hệ n:1 với model User (Tất cả bài viết này từ user_id nào tạo)
    function User()
    {
        return $this->belongsTo('App\User');
    }

    //Phương thức định nghĩa mối liên hệ nhiều: nhiều từ model Po (role_id quyền này có bao nhiêu người được sử dụng)
    function postcat()
    {
        //Phương thức định nghĩa liên kết mối liên hệ 1:n với model Postcat (Tất cả bài viết này từ danh mục nào)
        return $this->belongsTo('App\PostCat');
    }
}
