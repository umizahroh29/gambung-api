<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_category';

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

    public function product()
    {
    	return $this->hasMany('App\Product', 'main_category', 'code');
    }

    public function status()
    {
        return $this->hasMany('App\StatusCategory', 'category_code', 'code');
    }
}
