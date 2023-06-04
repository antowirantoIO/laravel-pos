<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'barcode',
        'price',
        'expired_date',
        'quantity',
        'status'
    ];
}
