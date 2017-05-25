<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', 'IndexController@login')->name('user.login');

Route::get('/administrator', function () {
    if (\App\Admin::all()->count() == 0) {
        return factory(App\Admin::class)->create();
    }
    return response()->json(['code' => 0, 'msg' => '管理员账号已经创建！']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/index', 'IndexController@index');
});
