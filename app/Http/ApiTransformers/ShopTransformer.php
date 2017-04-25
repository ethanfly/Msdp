<?php
/**
 * Created by PhpStorm.
 * User: ethan
 * Date: 2017/4/17
 * Time: 20:56
 */

namespace App\Http\ApiTransformers;


use App\Shop;
use League\Fractal\TransformerAbstract;

class ShopTransformer extends TransformerAbstract
{
    public function transform(Shop $shop)
    {
        $evaluates = $shop->evaluates;
        $score1 = collect();
        $score2 = collect();
        $score3 = collect();
        foreach ($evaluates as $evaluate) {
            $user = $evaluate->user;
            if ($user->type == 1) {
                $type1 = ($evaluate->field1 + $evaluate->field2 + $evaluate->field3 + $evaluate->field4) / 4;
                $score1->push($type1);
            } else if ($user->type == 2) {
                $type2 = ($evaluate->field1 + $evaluate->field2 + $evaluate->field3 + $evaluate->field4) / 4;
                $score2->push($type2);
            } else if ($user->type == 3) {
                $type3 = ($evaluate->field1 + $evaluate->field2 + $evaluate->field3 + $evaluate->field4) / 4;
                $score3->push($type3);
            }
        }


        if ($score1->count() == 0) {
            $score1 = 0;
        } else {
            $score1 = $score1->avg() * 0.3;
        }
        if ($score2->count() == 0) {
            $score2 = 1.5;
        } else {
            $score2 = $score2->avg() * 0.3;
        }
        if ($score3->count() == 0) {
            $score3 = 2;
        } else {
            $score3 = $score3->avg() * 0.4;
        }

        $score = $score1 + $score2 + $score3;

        return [
            'id' => $shop->id,
            'owner' => $shop->manager,
            'license' => $shop->number,
            'name' => $shop->name,
            'score' => round($score, 1)
        ];
    }
}