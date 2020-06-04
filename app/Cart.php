<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_cart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function product()
    {
    	return $this->belongsTo('App\Product', 'product_code', 'code');
    }

    public function cart_product_status()
    {
      return $this->hasOne('App\CartProductStatus', 'id_cart', 'id');
    }
}
