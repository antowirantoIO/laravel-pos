<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Supplier extends Model
{
    use HasFactory;
	
	protected $fillable =[
		'supplier_name',
		'address',
		'phone',
		//'avatar',
		'user_id',
		'created_at',
		'updated_at'
	];
	
	public function getAvatarUrl()
	{
		return Storage::url($this->avatar);
	}
}
