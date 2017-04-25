<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = [];

    public function shops()
    {
        return $this->hasMany('App\Shop');
    }

    public function types()
    {
        return $this->hasMany('App\Type');
    }

    public function evaluates()
    {
        return $this->hasMany('App\Evaluate');
    }

}
