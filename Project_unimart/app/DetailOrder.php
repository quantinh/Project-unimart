<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailOrder extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order_code',
        'price',
        'quantily',
        'sub_total',
        'product_id',
        'total_quantily',
        'total_price',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
