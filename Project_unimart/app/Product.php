<?php

namespace App;
use App\ProductCat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name_product',
        'slug',
        'price',
        'price_old',
        'quantily',
        'image',
        'brand_id',
        'color_id',
        'cat_id',
        'product_featured',
        'product_selling',
        'user_id',
        'detail',
        'description',
        'status',
        'created_at',
        'updated_at'
    ];

    //Phương thức định nghĩa liên kết mối liên hệ n:1 với model User (Tất cả bài viết này từ user_id nào tạo)
    function User()
    {
        return $this->belongsTo('App\User');
    }

    //Phương thức định nghĩa mối liên hệ nhiều: nhiều từ model Product (role_id quyền này có bao nhiêu người được sử dụng)
    function productcat()
    {
        return $this->belongsTo('App/ProductCat');
    }
}
