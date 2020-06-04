<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JiCash extends Model
{
    protected $table = "ji_cash";

    protected $fillable = [
        'id', 'username', 'balance'
    ];

    public function history()
    {
        return $this->hasMany('App\JiCashHistory', 'ji_cash_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'username', 'username');
    }
}
