<?php
/**
 * Created by PhpStorm.
 * User: ethan
 * Date: 2017/4/17
 * Time: 20:26
 */

namespace App\Http\Transformers;

use App\Market;
use League\Fractal\TransformerAbstract;

class MarketTransformer extends TransformerAbstract
{
    public function transform(Market $market)
    {
        return [
            'name' => $market->name,
            'img' => $market->img,
            'distance' => $market->distance
        ];
    }
}