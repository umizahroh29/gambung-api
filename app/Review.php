<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = ['review'];

    public function users()
    {
      return $this->belongsTo('App\User', 'id_users', 'id');
    }

    public function products()
    {
      return $this->belongsTo('App\Product', 'product_code', 'code');
    }

}
