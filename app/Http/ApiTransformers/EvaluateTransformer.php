<?php
/**
 * Created by PhpStorm.
 * User: ethan
 * Date: 2017/4/18
 * Time: 15:44
 */

namespace App\Http\ApiTransformers;


use App\Evaluate;
use League\Fractal\TransformerAbstract;

class EvaluateTransformer extends TransformerAbstract
{
    public function transform(Evaluate $evaluate)
    {
        if ($evaluate->market_id) {
            return [
                'id' => $evaluate->id,
                'headImgUrl' => $evaluate->user->avatarUrl,
                'name' => $evaluate->user->nickname,
                'time' => date('Y-m-d H:i:s', strtotime($evaluate->created_at)),
                'labelList' => $evaluate->feel?explode(',', $evaluate->feel):'',
                'scoreList' => [
                    ['name' => '市场环境', 'score' => $evaluate->field1],
                    ['name' => '食品安全', 'score' => $evaluate->field2],
                    ['name' => '管理服务', 'score' => $evaluate->field3],
                    ['name' => '诚信经营', 'score' => $evaluate->field4],
                ],
                'inputValue' => $evaluate->others
            ];
        } else if ($evaluate->shop_id) {
            return [
                'id' => $evaluate->id,
                'headImgUrl' => $evaluate->user->avatarUrl,
                'name' => $evaluate->user->nickname,
                'time' => date('Y-m-d H:i:s', strtotime($evaluate->created_at)),
                'labelList' => $evaluate->feel?explode(',', $evaluate->feel):'',
                'scoreList' => [
                    ['name' => '店铺卫生', 'score' => $evaluate->field1],
                    ['name' => '商品质量', 'score' => $evaluate->field2],
                    ['name' => '服务态度', 'score' => $evaluate->field3],
                    ['name' => '诚信经营', 'score' => $evaluate->field4],
                ],
                'inputValue' => $evaluate->others
            ];
        } else {
            return null;
        }
    }
}