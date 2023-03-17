<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //Thuộc tính
    protected $fillable = [
        'name_role',
        'description',
        'user_id',
        'created_at',
        'updated_at',
    ];
    //Phương thức định nghĩa mối liên hệ nhiều: nhiều từ model User (role_id quyền này có bao nhiêu người được sử dụng)
    function users()
    {
        return $this->belongsToMany('App\User');
    }
}
