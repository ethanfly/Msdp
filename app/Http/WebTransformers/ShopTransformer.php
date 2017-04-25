<?php
/**
 * Created by PhpStorm.
 * User: ethan
 * Date: 2017/4/23
 * Time: 21:48
 */

namespace App\Http\WebTransformers;


use App\Shop;
use League\Fractal\TransformerAbstract;

class ShopTransformer extends TransformerAbstract
{
    public function transform(Shop $shop)
    {
        return [
            'id' => $shop->id,
            'name' => $shop->name,
            'manager' => $shop->manager,
            'number' => $shop->number,
            'selectMarket' => [$shop->market->id, $shop->type->id],
            'market_type' => $shop->market->name . ' / ' . $shop->type->name,
            'created_at' => date('Y-m-d', strtotime($shop->created_at)),
        ];
    }
}