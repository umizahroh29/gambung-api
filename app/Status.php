<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_status';

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

    public function detail()
    {
        return $this->hasMany('App\StatusDetail', 'status_code', 'code');
    }

    public function category()
    {
        return $this->hasMany('App\Category', 'category_code', 'code');
    }

    public function cart_product_status()
    {
      return $this->hasMany('App\CartProductStatus', 'status_code', 'code');
    }

    public function transaction_detail_status()
    {
      return $this->hasMany('App\TransactionDetailStatus', 'status_code', 'code');
    }
}
