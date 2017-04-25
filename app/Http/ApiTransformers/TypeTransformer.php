<?php
/**
 * Created by PhpStorm.
 * User: ethan
 * Date: 2017/4/17
 * Time: 20:54
 */

namespace App\Http\ApiTransformers;


use App\Type;
use League\Fractal\TransformerAbstract;

class TypeTransformer extends TransformerAbstract
{
    public function transform(Type $type)
    {
        return [
            'id' => $type->id,
            'name' => $type->name,
            'selected'=>''
        ];
    }
}