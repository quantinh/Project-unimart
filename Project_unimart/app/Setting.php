<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use SoftDeletes;
    protected $fillable=[
    'config_key',
    'config_value',
    'status',
    'type',
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
