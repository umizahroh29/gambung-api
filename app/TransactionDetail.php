<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_transaction_detail';

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

    public function transaction()
    {
    	return $this->belongsTo('App\Transaction', 'transaction_code', 'code');
    }

    public function product()
    {
    	return $this->belongsTo('App\Product', 'product_code', 'code');
    }

    public function status()
    {
      return $this->hasOne('App\TransactionDetailStatus', 'id_detail', 'id');
    }

}
