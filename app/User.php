<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
  use Notifiable, HasApiTokens;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'name', 'email', 'password', 'username', 'phone', 'address_1','role', 'city', 'province'
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
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function store()
  {
    return $this->hasOne('App\Store', 'username', 'username');
  }

  public function messages()
  {
    return $this->hasMany('App\Message', 'id_users', 'id');
  }

  public function reviews()
  {
    return $this->hasMany('App\Review', 'id_users', 'id');
  }

  public function wishlists()
  {
    return $this->hasOne('App\Wishlist', 'id_users', 'id');
  }

  public function point()
  {
    return $this->hasMany('App\Point', 'id_users', 'id');
  }

  public function notification()
  {
    return $this->hasMany('App\Notification', 'id_users', 'id');
  }

  public function notification_from()
  {
    return $this->hasMany('App\Notification', 'id_users', 'id');
  }

  public function transaction()
  {
    return $this->hasMany('App\Transaction', 'username', 'username');
  }

    public function jicash()
    {
        return $this->hasOne('App\JiCash', 'username', 'username');
    }
}
