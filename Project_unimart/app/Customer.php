<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'fullname',
        'email',
        'address',
        'phone',
        'note',
        'status_customer',
        'created_at',
        'updated_at'
    ];
}
