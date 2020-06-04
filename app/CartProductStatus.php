<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartProductStatus extends Model
{
    protected $table = 'cart_product_status';

    public function cart()
    {
      return $this->belongsTo('App\Cart', 'id_cart', 'id');
    }

    public function status()
    {
      return $this->belongsTo('App\Status', 'status_code', 'code');
    }
}
