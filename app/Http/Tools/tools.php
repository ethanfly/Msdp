<?php

/**
 * Created by PhpStorm.
 * User: ethan
 * Date: 2017/4/20
 * Time: 15:14
 */

define('PI', 3.1415926535898);
define('EARTH_RADIUS', 6378.137);
//计算范围，可以做搜索用户
function GetRange($lat, $lon, $raidus)
{
    //计算纬度
    $degree = (24901 * 1609) / 360.0;
    $dpmLat = 1 / $degree;
    $radiusLat = $dpmLat * $raidus;
    $minLat = $lat - $radiusLat; //得到最小纬度
    $maxLat = $lat + $radiusLat; //得到最大纬度
    //计算经度
    $mpdLng = $degree * cos($lat * (PI / 180));
    $dpmLng = 1 / $mpdLng;
    $radiusLng = $dpmLng * $raidus;
    $minLng = $lon - $radiusLng; //得到最小经度
    $maxLng = $lon + $radiusLng; //得到最大经度
    //范围
    $range = array(
        'minLat' => $minLat,
        'maxLat' => $maxLat,
        'minLon' => $minLng,
        'maxLon' => $maxLng
    );
    return $range;
}

//获取2点之间的距离
function GetDistance($lat1, $lng1, $lat2, $lng2)
{
    $radLat1 = $lat1 * (PI / 180);
    $radLat2 = $lat2 * (PI / 180);
    $a = $radLat1 - $radLat2;
    $b = ($lng1 * (PI / 180)) - ($lng2 * (PI / 180));
    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
    $s = $s * EARTH_RADIUS;
    $s = round($s * 10000) / 10000;
    return $s;
}


if (!function_exists('paginate')) {
    function paginate(\Illuminate\Http\Request $request, $data, $perPage)
    {
        if ($request->has('page')) {

            $current_page = $request->get('page');

            $current_page = $current_page <= 0 ? 1 : $current_page;

        } else {

            $current_page = 1;

        }

        $item = array_slice($data, ($current_page - 1) * $perPage, $perPage); //注释1

        $total = count($data);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($item, $total, $perPage, $current_page, [

            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), //注释2

            'pageName' => 'page',

        ]);
        if ($request->has('latitude') && $request->has('longitude')) {
            $paginator->appends([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        }
        return $paginator;

    }
}

if (!function_exists('MarketTransform')) {
    function MarketTransform(\App\Market $market)
    {
        $evaluates = $market->evaluates;
        $shops = $market->shops;
        $shops->each(function ($shop) use ($evaluates) {
            $shop->evaluates->each(function ($evaluate) use ($evaluates) {
                $evaluates->push($evaluate);
            });
        });
        $score1 = collect();
        $score2 = collect();
        $score3 = collect();
        foreach ($evaluates as $evaluate) {
            $user = $evaluate->user;
            if ($user->type == 1) {
                $field1 = $evaluate->field1;
                $field2 = $evaluate->field2;
                $field3 = $evaluate->field3;
                $field4 = $evaluate->field4;
                $type1 = ($field1 + $field2 + $field3 + $field4) / 4;
                $score1->push([
                    'field1' => $field1,
                    'field2' => $field2,
                    'field3' => $field3,
                    'field4' => $field4,
                    'score' => $type1
                ]);
            } else if ($user->type == 2) {
                $field1 = $evaluate->field1;
                $field2 = $evaluate->field2;
                $field3 = $evaluate->field3;
                $field4 = $evaluate->field4;
                $type2 = ($field1 + $field2 + $field3 + $field4) / 4;
                $score2->push([
                    'field1' => $field1,
                    'field2' => $field2,
                    'field3' => $field3,
                    'field4' => $field4,
                    'score' => $type2
                ]);
            } else if ($user->type == 3) {
                $field1 = $evaluate->field1;
                $field2 = $evaluate->field2;
                $field3 = $evaluate->field3;
                $field4 = $evaluate->field4;
                $type3 = ($field1 + $field2 + $field3 + $field4) / 4;
                $score3->push([
                    'field1' => $field1,
                    'field2' => $field2,
                    'field3' => $field3,
                    'field4' => $field4,
                    'score' => $type3
                ]);
            }
        }


        if ($score1->count() == 0) {
            $score1 = collect([
                'field1' => 0,
                'field2' => 0,
                'field3' => 0,
                'field4' => 0,
                'score' => 0
            ]);
        } else {
            $score1 = collect([
                'field1' => $score1->avg('field1') * 0.3,
                'field2' => $score1->avg('field2') * 0.3,
                'field3' => $score1->avg('field3') * 0.3,
                'field4' => $score1->avg('field4') * 0.3,
                'score' => $score1->avg('score') * 0.3
            ]);
        }
        if ($score2->count() == 0) {
            $score2 = collect([
                'field1' => 1.5,
                'field2' => 1.5,
                'field3' => 1.5,
                'field4' => 1.5,
                'score' => 1.5
            ]);
        } else {
            $score2 = collect([
                'field1' => $score2->avg('field1') * 0.3,
                'field2' => $score2->avg('field2') * 0.3,
                'field3' => $score2->avg('field3') * 0.3,
                'field4' => $score2->avg('field4') * 0.3,
                'score' => $score2->avg('score') * 0.3
            ]);
        }
        if ($score3->count() == 0) {
            $score3 = collect([
                'field1' => 2,
                'field2' => 2,
                'field3' => 2,
                'field4' => 2,
                'score' => 2
            ]);
        } else {
            $score3 = collect([
                'field1' => $score3->avg('field1') * 0.4,
                'field2' => $score3->avg('field2') * 0.4,
                'field3' => $score3->avg('field3') * 0.4,
                'field4' => $score3->avg('field4') * 0.4,
                'score' => $score3->avg('score') * 0.4
            ]);
        }

        $field1 = $score1['field1'] + $score2['field1'] + $score3['field1'];
        $field2 = $score1['field2'] + $score2['field2'] + $score3['field2'];
        $field3 = $score1['field3'] + $score2['field3'] + $score3['field3'];
        $field4 = $score1['field4'] + $score2['field4'] + $score3['field4'];
        $score = $score1['score'] + $score2['score'] + $score3['score'];

        return [
            'id' => $market->id,
            'name' => $market->name,
            'img' => $market->img,
            'dist' => round($market->distance, 2),
            'score' => round($score, 1),
            'scoreList' => [
                ['name' => '市场环境', 'score' => round($field1, 1)],
                ['name' => '食品安全', 'score' => round($field2, 1)],
                ['name' => '管理服务', 'score' => round($field3, 1)],
                ['name' => '诚信经营', 'score' => round($field4, 1)],
            ],
            'flexClass' => ''
        ];
    }
}


