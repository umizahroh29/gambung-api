<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoucherTerms extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_voucher_terms';

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

    public function voucher()
    {
    	return $this->belongsTo('App\Voucher', 'voucher_code', 'code');
    }

    public function store()
    {
      return $this->belongsTo('App\Store', 'store_code', 'code');
    }
}
