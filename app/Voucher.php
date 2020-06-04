<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_voucher';

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
    	return $this->hasMany('App\Transaction', 'voucher_code', 'code');
    }

    public function term()
    {
    	return $this->hasMany('App\VoucherTerms', 'voucher_code', 'code');
    }
}
