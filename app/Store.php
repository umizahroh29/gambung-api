<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_store';

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

    public function expedition()
    {
    	return $this->hasMany('App\StoreExpedition', 'store_code', 'code');
    }

    public function product()
    {
    	return $this->hasMany('App\Product', 'store_code', 'code');
    }

    public function users()
    {
      return $this->belongsTo('App\User', 'username', 'username');
    }

    public function voucher()
    {
      return $this->hasMany('App\VoucherTerms', 'store_code', 'code');
    }

}
