<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = [];
    protected $hidden = ['openid'];

    public function evaluates()
    {
        return $this->hasMany('App\Evaluate');
    }
}
