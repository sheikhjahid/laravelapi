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

///////////////////////////////////////////////////////////////////////////
Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('register','Api\ApiController@register');
Route::post('login','Api\ApiController@login');
Route::group(['middleware' => ['jwt.auth','admin']], function () 
{
	Route::get('get-profile-admin', 'Api\ApiController@getAuthenticatedUser');
	Route::get('allTracks','Api\MusicController@index');
	Route::post('addTrack','Api\MusicController@add');
	Route::get('searchTrack/{id}', 'Api\MusicController@search');
	Route::post('updateTrack/{id}','Api\MusicController@update');
	Route::get('deleteTrack/{id}','Api\MusicController@delete');
	Route::get('allUsers','Api\ApiController@allUser');
	Route::post('searchUser','Api\ApiController@searchUser');
	Route::post('updateUser/{id}','Api\ApiController@updateUser');
	Route::get('deleteUser/{id}','Api\ApiController@deleteUser');
	Route::post('add_role','Api\ApiController@addRole');
    Route::post('add_permissions','Api\ApiController@addPermissions');
    Route::post('assign_role','Api\ApiController@assignRole');
    Route::post('assign_permission','Api\ApiController@assignPermission');
    Route::get('get_admin','Api\ApiController@getAdmin');
	Route::get('logout-admin','Api\ApiController@logout');
});

Route::group(['middleware'=>['jwt.auth','user']], function()
{
	Route::get('get-profile-user', 'Api\ApiController@getAuthenticatedUser');
	Route::get('allTrack','Api\MusicController@index');
	Route::post('searchTrack', 'Api\MusicController@searchTrack');
    Route::get('get_user','Api\ApiController@getUser');
	Route::get('logout-user','Api\ApiController@logout');

});

///////////////////////////////////////////////////////////////////////////


