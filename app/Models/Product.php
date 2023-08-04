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
        'purchase_price',
        'expired_date',
        'quantity',
        'status',
        'uom'
    ];

    // craete enum for uom in indonesia
    const UOM = [
        'pcs' => 'Pcs',
        'box' => 'Box',
        'lusin' => 'Lusin',
        'kodi' => 'Kodi',
        'rim' => 'Rim',
        'gross' => 'Gross',
        'meter' => 'Meter',
        'sachet' => 'Sachet',
        'centimeter' => 'Centimeter',
        'milimeter' => 'Milimeter',
        'liter' => 'Liter',
        'mililiter' => 'Mililiter',
        'gram' => 'Gram',
        'miligram' => 'Miligram',
        'kilogram' => 'Kilogram',
        'ton' => 'Ton',
        'kwintal' => 'Kwintal',
        'ons' => 'Ons',
        'mg' => 'MG',
        'ml' => 'ML',
        'cc' => 'CC',
        'buah' => 'Buah',
        'butir' => 'Butir',
        'lembar' => 'Lembar',
        'batang' => 'Batang',
        'kantong' => 'Kantong',
        'karung' => 'Karung',
    ];

    public function hpp()
    {
        return $this->hasMany(HPPProduct::class)->orderBy('created_at', 'ASC');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
}
