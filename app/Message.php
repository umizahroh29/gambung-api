<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = ['message'];

    public function dari()
    {
      return $this->belongsTo('App\User', 'from_user', 'id');
    }

    public function tujuan()
    {
      return $this->belongsTo('App\User', 'to_user', 'id');
    }
}
