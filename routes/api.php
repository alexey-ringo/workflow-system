<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', 'AuthController@login');
//Route::post('register', 'AuthController@register');

//Группа роутов для пользователей

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/logout', 'AuthController@logout');
    Route::apiResource('/users','UserController');
    Route::apiResource('/roles','RoleController');
    Route::apiResource('/permissions','PermissionController');
    Route::apiResource('/groups','GroupController');
    Route::apiResource('/missions','MissionController');
    Route::apiResource('/tasks','TaskController');
    //Route::get('/current-user', 'DashboardController@currentUser');
    //Route::apiResource('/users','UserController');
    
    
    
    
    //Route::get('/', 'DashboardController@dashboard')->name('admin.dashboard');
    
    //['as'=>'admin'] - префикс для полного имени в именнованном маршруте (напр - admin.category.create)
    //для исключения пересечения с др именнованными ресурсами
    //Route::post('/image-upload', 'ImageController@upload')->name('admin.upload_img');
    
    //Route::resource('/product', 'AdminProductController', ['as'=>'admin'])->name('product');
    //Route::resource('/user', 'AdminUserController', ['as'=>'admin']);
    
    //Route::group(['prefix' => 'user_management', 'namespace' => 'UserManagement'], function() {
    //    Route::resource('/user', 'UserController', ['as' => 'admin.user_management']);
    //});
});
