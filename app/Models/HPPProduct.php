<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HPPProduct extends Model
{
    use HasFactory;

    protected $table = 'hpp_product';

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.');
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }
}
