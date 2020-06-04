<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_status_category';

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

    public function status()
    {
        return $this->belongsTo('App\Status', 'status_code', 'code');
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_code', 'code');
    }
}
