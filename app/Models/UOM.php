<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UOM extends Model
{
    use HasFactory;

    protected $table = 'uoms';

    protected $fillable = [
        'name'
    ];
}
