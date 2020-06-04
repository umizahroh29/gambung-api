<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'stock'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function store()
    {
    	return $this->belongsTo('App\Store', 'store_code', 'code');
    }

    public function cart()
    {
    	return $this->hasMany('App\Cart', 'product_code', 'code');
    }

    public function images()
    {
    	return $this->hasMany('App\ProductImages', 'product_code', 'code');
    }

    public function transaction_detail()
    {
    	return $this->hasMany('App\TransactionDetail', 'product_code', 'code');
    }

    public function product_detail()
    {
    	return $this->hasMany('App\ProductDetail', 'product_code', 'code');
    }

    public function sub_category()
    {
        return $this->belongsTo('App\Category', 'sub_category', 'code');
    }

    public function main_category()
    {
        return $this->belongsTo('App\Category', 'main_category', 'code');
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'main_category', 'code');
    }

    public function reviews()
    {
      return $this->hasMany('App\Review', 'product_code', 'code');
    }

    public function wishlists()
    {
      return $this->hasMany('App\Wishlist', 'product_code', 'code');
    }
}
