<?php
/**
 * Created by PhpStorm.
 * User: ethan
 * Date: 2017/4/23
 * Time: 21:48
 */

namespace App\Http\WebTransformers;


use App\Market;
use League\Fractal\TransformerAbstract;

class MarketTransformer extends TransformerAbstract
{
    public function transform(Market $market)
    {
        return [
            'id' => $market->id,
            'name' => $market->name,
            'img' => $market->img,
            'latitude' => $market->latitude,
            'longitude' => $market->longitude,
            'created_at' => date('Y-m-d', strtotime($market->created_at)),
            'types' => $market->types
        ];
    }
}