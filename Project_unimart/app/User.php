<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Phương thức định nghĩa mối liên hệ 1:n từ model Post (user_id tạo ra bao nhiêu bài viết)
    function posts()
    {
        return $this->hasMany('App\Post');
    }
    //Phương thức định nghĩa mối liên hệ nhiều: nhiều từ model Role (role_id quyền này có bao nhiêu người được sử dụng)
    function roles()
    {
        //Lấy tất cả các quyền do của 1 users, bảng roles chứa khóa ngoại là user_id: nhiều quyền
        return $this->hasMany('App\Role');
    }
    //Phương thức định nghĩa mối liên hệ nhiều: nhiều từ model sản phẩm
    function products()
    {
        return $this->hasMany('App\Product');
    }
}
