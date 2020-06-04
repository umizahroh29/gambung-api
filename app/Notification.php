<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    public function users(){
      return $this->belongsTo('App\User', 'id_users', 'id');
    }

    public function from_user(){
      return $this->belongsTo('App\User', 'notification_from', 'id');
    }
}
