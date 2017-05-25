<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {
        $api->post('login', 'ApiController@login');
        $api->get('market', 'ApiController@market');
        $api->get('type', 'ApiController@type');
        $api->get('shop', 'ApiController@shop');
        $api->get('evaluate', 'ApiController@evaluateList');
        $api->post('evaluate', 'ApiController@evaluateAdd');
        $api->get('banner', 'ApiController@getBanner');
//        后台接口
        $api->group(['prefix' => 'admin'], function ($api) {
            $api->post('banner', 'IndexController@setBanner');
            $api->delete('banner', 'IndexController@deleteBanner');
            $api->post('upload', 'IndexController@upload');
            $api->get('setting', 'IndexController@getSetting');
            $api->post('setting', 'IndexController@setSetting');
            $api->resource('user', 'UserController');
            $api->resource('market', 'MarketController');
            $api->resource('type', 'TypeController');
            $api->resource('shop', 'ShopController');
            $api->resource('comment', 'CommentController');
        });
    });
});

//Route::group(['middleware' => 'api'], function () {
//    Route::resource('user','UserController');
//});

