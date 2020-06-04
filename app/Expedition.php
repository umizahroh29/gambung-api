<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expedition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_expedition';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'created_at', 'updated_at'
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
    	return $this->hasMany('App\StoreExpedition', 'expedition_code', 'code');
    }

}
