<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'image_desc',
        'product_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    function products()
    {
        return $this->hasMany('App\Product');
    }
}
