<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/





// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


// CRON
Route::get('cron', 'CronController@index');



// AUTOMATION - Friends
Route::get('friends', 'FriendController@index');

// AUTOMATION - Followers
Route::get('followers', 'FollowerController@index');

// AUTOMATION - Whitelist
Route::get('whitelist', 'WhitelistController@index');

// AUTOMATION - Model Account and Target Users
Route::get('copy', 'ModelAccountController@filter');

// FILTER
Route::get('filter', 'FilterController@index');



// FOLLOW
Route::get('follow', 'FollowController@index');

// UNFOLLOW
Route::get('unfollow', 'UnfollowController@index');





// TEST
Route::get('test', 'CronController@test');




Route::group(array('middleware' => 'auth'), function(){
	
	Route::get('/', 'AdminController@index');

	Route::get('/home', 'AdminController@index');

	// USERS
	Route::resource('users', 'UserController');
	Route::get('auth/register/{user_id}/delete', 'UserController@destroy');

	// SET USER
	Route::get('set-user/{id}', 'ModelAccountController@index');
	Route::post('set-user/{id}/search', 'ModelAccountController@search');
	Route::post('set-user/{id}/{model_account_id}', 'ModelAccountController@store');
	Route::get('set-user/{id}/{model_account_id}/destroy', 'ModelAccountController@destroy');
	
	// ACCOUNTS
	Route::resource('accounts', 'AccountController');
	Route::get('accounts/destroy/{id}', 'AccountController@destroy');
	
	// SETTINGS
	Route::get('settings', 'SettingController@index');

	// SORT
	Route::post('set-user/{id}/sort-order', 'SortController@modelAccountSortOrder');
	
	

});

