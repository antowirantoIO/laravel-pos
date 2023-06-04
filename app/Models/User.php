<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function cart()
    {
        return $this->belongsToMany(Product::class, 'user_cart')->withPivot('quantity');
    }

    public function purchase()
    {
        return $this->belongsToMany(Product::class, 'user_purchase')->withPivot('quantity');
    }

    public function getFullname()
    {
        return $this->username;
    }

    public function getAvatar()
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->name);
    }
}
