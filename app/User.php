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

    public function getGenderAttribute($value)
    {
        switch ($value) {
            case 0:
                return '未设置';
            case 1:
                return '男';
            case 2:
                return '女';
            default:
                return '未知';
        }
    }
}
