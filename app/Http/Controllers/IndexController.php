<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelChen\MyFlash\MyFlash;

class IndexController extends Controller
{
    //
    public function login(Request $request)
    {
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password], $request->remember)) {
            MyFlash::success('欢迎回来，' . $request->name);
            return redirect('/index');
        } else {
//            return json_encode(['code'=>0,'msg'=>'用户名密码错误！']);
            MyFlash::error('用户名密码错误!');
            return redirect('/');
        }
    }

    public function index()
    {
        return view('app');
    }

    public function getSetting(Request $request)
    {
        $WX_APP_ID = Setting::where('key', 'WX_APP_ID')->first()->value;
        $WX_APP_SECRET = Setting::where('key', 'WX_APP_SECRET')->first()->value;
        $BD_AK = Setting::where('key', 'BD_AK')->first()->value;
        $banner = Setting::where('key', 'banner')->get();
        $banner = $banner->map(function ($item, $key) {
            return [
                'name' => $item->key,
                'url' => $item->value
            ];
        });
        return response()->json([
            'BD_AK' => $BD_AK,
            'WX_APP_SECRET' => $WX_APP_SECRET,
            'WX_APP_ID' => $WX_APP_ID,
            'banner' => $banner
        ]);
    }

    public function setSetting(Request $request)
    {
        $WX_APP_ID = Setting::where('key', 'WX_APP_ID')->update(['value' => $request->WX_APP_ID]);
        $WX_APP_SECRET = Setting::where('key', 'WX_APP_SECRET')->update(['value' => $request->WX_APP_SECRET]);
        $BD_AK = Setting::where('key', 'BD_AK')->update(['value' => $request->BD_AK]);
        $PAS = $request->PAS;
        Admin::where('name', 'admin')->update(['password' => bcrypt($PAS)]);
        if ($WX_APP_ID && $WX_APP_SECRET && $BD_AK) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }
    }

    public function setBanner(Request $request)
    {
        $banner = $request->banner;
        if (Setting::create(['key' => 'banner', 'value' => $banner])) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }
    }

    public function deleteBanner(Request $request)
    {
        $banner = $request->banner;
        if (Setting::where([
            ['key', '=', 'banner'],
            ['value', '=', $banner]
        ])->delete()
        ) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
        }
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $name = str_random(8) . time() . '.' . $extension;
            $path = $file->move('upload', $name);
            $path = asset(str_replace('\\', '/', $path));
            return response()->json(['code' => 1, 'url' => $path]);
        }
    }
}
