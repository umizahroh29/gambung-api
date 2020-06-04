<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
  protected $table = 'wishlists';

  public function users()
  {
    return $this->belongsTo('App\User', 'id_users', 'id');
  }

  public function products()
  {
    return $this->belongsTo('App\Product', 'product_code', 'code');
  }
}
