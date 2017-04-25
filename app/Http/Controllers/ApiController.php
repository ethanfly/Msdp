<?php

namespace App\Http\Controllers;

use App\Evaluate;
use App\Http\ApiTransformers\EvaluateTransformer;
use App\Http\ApiTransformers\ShopTransformer;
use App\Http\ApiTransformers\TypeTransformer;
use App\Market;
use App\Setting;
use App\Shop;
use App\Type;
use App\User;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class ApiController extends Controller
{
    //
    private $BD_AK, $WX_APP_SECRET, $WX_APP_ID;

    public function login(Request $request)
    {
        if ($request->has('info') && $request->has('code')) {
            $info = $request->info;
            $code = $request->code;

            $WX_APP_ID = Setting::where([
                ['key', '=', 'WX_APP_ID']
            ])->first();

            $WX_APP_SECRET = Setting::where([
                ['key', '=', 'WX_APP_SECRET']
            ])->first();

            if ($WX_APP_SECRET) {
                $this->WX_APP_SECRET = $WX_APP_SECRET->value;
            } else {
                return response()->json(['code' => 0, 'msg' => '请在后台配置微信APP_SECRET！']);
            }

            if ($WX_APP_ID) {
                $this->WX_APP_ID = $WX_APP_ID->value;
            } else {
                return response()->json(['code' => 0, 'msg' => '请在后台配置百度地图APP_ID！']);
            }

            $response = Curl::to('https://api.weixin.qq.com/sns/jscode2session')
                ->withData([
                    'appid' => $this->WX_APP_ID,
                    'secret' => $this->WX_APP_SECRET,
                    'js_code' => $code,
                    'grant_type' => 'authorization_code'
                ])
                ->get();
            $response = json_decode($response);
//            return var_dump($response);
            $user = [
                'openid' => $response->openid,
                'nickname' => $info['nickName'],
                'avatarUrl' => $info['avatarUrl'],
                'gender' => $info['gender'],
                'city' => $info['city'],
                'province' => $info['province'],
                'country' => $info['country']
            ];
            $user = User::firstOrCreate($user);
            if ($user) {
                return response()->json(['code' => 1, 'msg' => '登录成功！', 'user_id' => $user->id]);
            } else {
                return response()->json(['code' => 0, 'msg' => '登录失败！']);
            }
        } else {
            return response()->json(['code' => 0, 'msg' => '登录失败！']);
        }
    }

    public function market(Request $request)
    {
        if ($request->has('latitude') && $request->has('longitude')) {
            $setting = Setting::where([
                ['key', '=', 'BD_AK']
            ])->first();
            if ($setting) {
                $this->BD_AK = $setting->value;
            } else {
                return response()->json(['code' => 0, 'msg' => '请在后台配置百度地图app_key！']);
            }

            $longitude = $request->longitude; //经度
            $latitude = $request->latitude;//纬度
            $bd09ll = Curl::to('http://api.map.baidu.com/geoconv/v1/')
                ->withData([
                    'coords' => $longitude . ',' . $latitude,
                    'ak' => $this->BD_AK,
                ])
                ->get();
            $bd09ll = json_decode($bd09ll);
            if ($bd09ll->status == 0) {
                $bd_latitude = $bd09ll->result[0]->y;
                $bd_longitude = $bd09ll->result[0]->x;
                $markets = Market::all();
                foreach ($markets as $market) {
                    $market->distance = GetDistance($market->latitude, $market->longitude, $bd_latitude, $bd_longitude);
                }
                $markets = $markets->sortBy('distance')->values();
                $markets = $markets->transform(function ($item) {
                    return MarketTransform($item);
                });
                $markets = paginate($request, $markets->toArray(), 10);
                return response()->json($markets);
            } else {
                return response()->json(['code' => $bd09ll->status, 'msg' => '转换异常！']);
            }
        } else {
            return response()->json(['code' => 0, 'msg' => '传入经纬度！']);
        }
    }

    public function type(Request $request)
    {
        if ($request->has('market_id')) {
            $types = Type::where('market_id', $request->market_id)->paginate(10);
            return $this->response()->paginator($types, new TypeTransformer());
        } else {
            return $this->response()->errorBadRequest('请传入market_id');
        }
    }

    public function shop(Request $request)
    {
        if ($request->has('type_id')) {
            $types = Shop::where('type_id', $request->type_id)->paginate(10);
            return $this->response()->paginator($types, new ShopTransformer());
        } else {
            return $this->response()->errorBadRequest('请传入type_id');
        }
    }

    public function evaluateList(Request $request)
    {
        $evaluates = Evaluate::orderBy('created_at', 'desc')->paginate(10);
        if ($request->has('shop_id')) {
            $evaluates = Evaluate::where('shop_id', $request->shop_id)->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->has('market_id')) {
            $evaluates = Evaluate::where('market_id', $request->market_id)->orderBy('created_at', 'desc')->paginate(10);
        }

        return $this->response()->paginator($evaluates, new EvaluateTransformer());
    }

    public function evaluateAdd(Request $request)
    {
        if (($request->has('shop_id') || $request->has('market_id')) && $request->has('user_id')) {
            $evaluate = $request->all();
            $evaluate = Evaluate::create($evaluate);
            return $this->response()->item($evaluate, new EvaluateTransformer());
        } else {
            return $this->response()->errorBadRequest('请传入shop_id或者market_id');
        }
    }
}
