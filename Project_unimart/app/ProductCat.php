<?php

namespace App;

use App\User;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCat extends Model
{
    //Dùng phần này để có thể sử dụng phương thức withTrash theo eloquent ORM
    use SoftDeletes;

    //Được hiểu model này có tương tác với table catposts trên csdl
    protected $fillable = [
        'cat_name',
        'slug',
        'status',
        'icon_cat',
        'parent_id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //Phương thức định nghĩa mối liên hệ 1:n lấy ra danh mục con thuộc cha nào đó
    public function categoryChildrent()
    {
        return $this->hasMany(ProductCat::class, 'parent_id');
    }
    //Phương thức định nghĩa mỗi liên hệ 1:n
    public function products()
    {
        return $this->hasMany(Product::class, 'cat_id');
    }
    //Phương thức định nghĩa mối liên hệ nhiều: nhiều từ model User
    function users()
    {
        return $this->belongsTo('App\User');
    }
}
