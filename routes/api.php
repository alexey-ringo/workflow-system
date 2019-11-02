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
    Route::get('/logged-user', 'DashboardController@loggedUser');
    Route::get('/permissions-menu', 'DashboardController@permissionsMenu');
    Route::get('/search-phone', 'SearchController@searchPhone');
    Route::get('/search-surname', 'SearchController@searchSurname');
    Route::get('/frontrequest-deadlines', 'FrontRequestController@getDeadlines');
    Route::get('/frontrequest-permission-names', 'FrontRequestController@getPermissionNames');
    Route::apiResource('/users','UserController');
    Route::apiResource('/roles','RoleController');
    Route::apiResource('/permissions','PermissionController');
    Route::apiResource('/groups','GroupController');
    Route::apiResource('/processes','ProcessController');
    Route::apiResource('/tasks','TaskController');
    Route::apiResource('/comments','CommentController');
    Route::apiResource('/routes','RouteController');
    Route::apiResource('/customers','CustomerController');
    Route::apiResource('/phones','PhoneController');
    Route::apiResource('/contracts','ContractController');
    Route::apiResource('/tariffs','TariffController');
    //Route::get('/current-user', 'DashboardController@currentUser');
    //Route::apiResource('/users','UserController');
    
    Route::group([/*'prefix' => 'bot', */'namespace' => 'Bot'], function() {
        Route::get('/telegram-index', 'TelegramSettingController@index');
        Route::post('/telegram-store', 'TelegramSettingController@store');
        Route::post('/telegram-setwebhook', 'TelegramSettingController@setWebhook');
        Route::post('/telegram-suspendwebhook', 'TelegramSettingController@suspendWebhook');
        Route::post('/telegram-getwebhookinfo', 'TelegramSettingController@getWebhookInfo');
    });
    
    
    
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


