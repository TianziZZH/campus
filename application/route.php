<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//return [
//    '__pattern__' => [
//        'name' => '\w+',
//    ],
//    '[hello]'     => [
//        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//        ':name' => ['index/hello', ['method' => 'post']],
//    ],
//
//];

use think\Route;

Route::post('api/:version/test','api/:version.Test/Test');

Route::post('api/:version/login','api/:version.Login/userLogin');

Route::get('api/:version/user','api/:version.User/show');
Route::get('api/:version/user/publish','api/:version.User/showPublish');
Route::get('api/:version/user/receive','api/:version.User/showReceive');

Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');

Route::get('api/:version/information','api/:version.Information/show');
Route::get('api/:version/information/:id','api/:version.Information/showdetail');

Route::post('api/:version/token/user','api/:version.Token/getToken');

Route::get('api/:version/xianyu','api/:version.Xianyu/xianyu');
Route::get('api/:version/xianyu/cancel/:id','api/:version.Xianyu/cancel',[],['id'=>'\d+']);
Route::post('api/:version/xianyu/create','api/:version.Xianyu/create');

Route::get('api/:version/paotui','api/:version.Paotui/paotui');
Route::get('api/:version/paotui/:id','api/:version.Paotui/take',[],['id'=>'\d+']);
Route::get('api/:version/paotui/finish/:id','api/:version.Paotui/finish',[],['id'=>'\d+']);
Route::post('api/:version/paotui/create','api/:version.Paotui/create');
Route::post('api/:version/paotui/judge','api/:version.Paotui/judge');
Route::get('api/:version/paotui/cancel/:id','api/:version.Paotui/cancel');