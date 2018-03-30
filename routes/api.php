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
Route::group(['middleware' => ['jwt.auth']], function () 
{
	Route::get('get-profile', 'Api\ApiController@getAuthenticatedUser');
	Route::get('allTracks','Api\MusicController@index');
	Route::post('addTrack','Api\MusicController@add');
	Route::get('searchTrack/{id}', 'Api\MusicController@search');
	Route::post('updateTrack/{id}','Api\MusicController@update');
	Route::get('deleteTrack/{id}','Api\MusicController@delete');
	Route::get('add_role','Api\ApiController@addRole');
    Route::post('add_permissions','Api\ApiController@addPermissions');
    Route::get('assign_role','Api\ApiController@assignRole');
    Route::get('assign_permission','Api\ApiController@assignPermission');
	Route::get('logout','Api\ApiController@logout');
});

///////////////////////////////////////////////////////////////////////////


