<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_transaction';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'username', 'address_1', 'phone', 'total_product', 'total_weight', 'total_quantity', 'voucher_code', 'shipping_charges', 'total_amount', 'discount_pct', 'discount_amount', 'grand_total_amount', 'created_by', 'updated_by', 'created_at', 'updated_at',
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
        return $this->hasMany('App\TransactionDetail', 'transaction_code', 'code');
    }

    public function history()
    {
        return $this->hasMany('App\TransactionHistory', 'transaction_code', 'code');
    }

    public function payment()
    {
        return $this->hasOne('App\TransactionPayment', 'transaction_code', 'code');
    }

    public function voucher()
    {
        return $this->belongsTo('App\Voucher', 'voucher_code', 'code');
    }

    public function point()
    {
        return $this->hasOne('App\Point', 'id_transaction', 'id');
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'username', 'username');
    }
}
