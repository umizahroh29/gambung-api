<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreExpedition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_store_expedition';

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

    public function store()
    {
    	return $this->belongsTo('App\Store', 'store_code', 'code');
    }

    public function expedition()
    {
    	return $this->belongsTo('App\Expedition', 'expedition_code', 'code');
    }
}
