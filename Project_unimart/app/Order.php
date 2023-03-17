<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; //Phải khai báo khi tạo model theo kiểu Query builder
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'order_code',
        'customer_id',
        'total_quantily',
        'total_price',
        'status',
        'payment',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
