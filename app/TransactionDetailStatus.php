<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetailStatus extends Model
{
    protected $table = "transaction_detail_status";

    public function detail()
    {
      return $this->belongsTo('App\TransactionDetail', 'id_detail', 'id');
    }

    public function status()
    {
      return $this->belongsTo('App\Status', 'status_code', 'code');
    }

}
