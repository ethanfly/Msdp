<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = [];

    public function market()
    {
        return $this->belongsTo('App\Market');
    }
    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function evaluates()
    {
        return $this->hasMany('App\Evaluate');
    }
}
