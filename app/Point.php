<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $table = "points";

    public function users()
    {
      return $this->belongsTo('App\User', 'id_users', 'id');
    }

    public function transaction()
    {
      return $this->belongsTo('App\User', 'id_transaction', 'id');
    }
}
