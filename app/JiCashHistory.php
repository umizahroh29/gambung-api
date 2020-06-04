<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JiCashHistory extends Model
{
    protected $table = "ji_cash_history";

    protected $fillable = [
        'id', 'ji_cash_id', 'transaction_type', 'amount', 'is_topup_approved', 'topup_proof_image', 'is_withdrawal', 'withdrawal_approved_by'
    ];

    public function jicash()
    {
        return $this->belongsTo('App\JiCash', 'ji_cash_id', 'id');
    }
}
