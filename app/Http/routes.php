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

Route::group(array('middleware' => 'auth'), function(){
	
	Route::get('/', 'AdminController@index');

	Route::get('/home', 'AdminController@index');

	// USERS
	Route::resource('users', 'UserController');
	Route::get('auth/register/{user_id}/delete', 'UserController@destroy');

	// ACCOUNTS
	Route::resource('accounts', 'AccountController');
	Route::get('accounts/destroy/{id}', 'AccountController@destroy');

	// SET USER
	Route::get('set-user/{id}', 'ModelAccountController@index');
	Route::post('set-user/{id}/search', 'ModelAccountController@search');
	Route::get('set-user/{id}/{model_account_id}', 'ModelAccountController@store');
	Route::get('set-user/{id}/{model_account_id}/destroy', 'ModelAccountController@destroy');

	// AUTOMATION - Cron Job
	Route::get('cron', 'CronController@index');

	// AUTOMATION - Friends, Followers, Whitelist, Model Account Temp Users
	Route::get('automate', 'AutomationController@index');

	// AUTOMATION - Filter for Temp Accounts into Target Table
	Route::get('filter', 'FilterController@index');

	// AUTOMATION - Follow accounts in Target_Users Table
	Route::get('follow', 'FollowController@index');











	// GLOBAL Settings routes...
	Route::resource('settings', 'SettingController');
	Route::get('settings/new/cta/{column_id}', 'SettingController@create');
	Route::get('settings/new/social', 'SettingController@createSocial');
	Route::get('settings/{id}/delete', 'SettingController@destroy');
	Route::get('settings/{id}/delete/image', 'SettingController@destroyImage');
	Route::get('settings/{id}/duplicate', 'SettingController@duplicate');

	// Movie routes
	Route::resource('admin/movies', 'MovieController');
	Route::get('admin/movies/{movie_id}/delete', 'MovieController@destroy');
	
	// Call to Action routes
	Route::resource('admin/movies/{movie_id}/edit/sections/{section_id}/cta', 'CtaController');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/cta/{cta_id}/delete', 'CtaController@destroy');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/cta/{cta_id}/duplicate', 'CtaController@duplicate');
	
	// Section routes
	Route::resource('admin/movies/{movie_id}/edit/sections', 'SectionController');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/delete', 'SectionController@destroy');
	Route::post('admin/movies/{movie_id}/edit/sections/update', 'SectionController@update');
	Route::post('admin/movies/{movie_id}/edit/sections/new', 'SectionController@newSection');
	
	// Ticket routes
	Route::resource('admin/movies/{movie_id}/edit/sections/{section_id}/tickets', 'TicketController');
	
	// Video routes
	Route::resource('admin/movies/{movie_id}/edit/sections/{section_id}/videos', 'VideoController');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/videos/{video_id}/delete', 'VideoController@destroy');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/videos/{video_id}/duplicate', 'VideoController@duplicate');
	
	// About routes - includes movie about and people
	Route::resource('admin/movies/{movie_id}/edit/sections/{section_id}/about', 'AboutController');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/about/{person_id}/delete', 'AboutController@destroy');
	
	// Image routes
	Route::resource('admin/movies/{movie_id}/edit/sections/{section_id}/gallery', 'ImageController');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/upload-image', 'ImageController@store');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/upload', 'ImageController@create');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/upload-gallery', 'ImageController@createMultiple');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/image/{image_id}/delete', 'ImageController@destroy');
	
	// Featured Content / Partners / Reviews & Awards routes
	Route::resource('admin/movies/{movie_id}/edit/sections/{section_id}/featured/', 'FeaturedContentController');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/featured/{partner_id}/delete', 'FeaturedContentController@destroy');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/credit/upload', 'FeaturedContentController@creditImage');
	
	// Release Dates routes
	Route::resource('admin/movies/{movie_id}/edit/sections/{section_id}/release-date', 'ReleaseDateController');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/release-date/{release_date_id}/delete', 'ReleaseDateController@destroy');
	Route::get('admin/movies/{movie_id}/edit/sections/{section_id}/release-date/{release_date_id}/duplicate', 'ReleaseDateController@duplicate');
	
	
	// Publishing routes
	Route::resource('admin/movies/{movie_id}/edit/publishing', 'PublishingController');	
	Route::get('admin/movies/{movie_id}/publishing/{id}/env', 'PublishingController@env');
	Route::post('admin/movies/{movie_id}/publishing/env', 'PublishingController@env');
	Route::resource('admin/movies/{movie_id}/publish/html', 'PublishingController@createHTML');
	Route::resource('{movie_id}/publish/html', 'PublishingController@createHTML');
	
	// Design routes
	Route::resource('admin/movies/{movie_id}/edit/design', 'DesignController');
	Route::get('admin/movies/{movie_id}/edit/design/{id}/delete', 'DesignController@destroy');
	Route::post('admin/movies/{movie_id}/edit/design/upload', 'DesignController@upload');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/custom-background', 'DesignController@store');
	
	// Social Media routes
	Route::resource('admin/movies/{movie_id}/edit/seo-social', 'SEOandSocialController');
	Route::get('admin/movies/{movie_id}/edit/seo-social/{social_id}/delete', 'SEOandSocialController@destroy');
	Route::get('admin/movies/{movie_id}/edit/seo-social/{social_id}/duplicate', 'SEOandSocialController@duplicate');
	
	
	
	
	// SORT routes
	Route::post('admin/movies/{movie_id}/edit/sort-order', 'SortController@sectionSortOrder');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/videos/sort-order', 'SortController@videoSortOrder');
	Route::post('settings/sort-order', 'SortController@settingSortOrder');
	Route::post('admin/movies/sort-order', 'SortController@movieSortOrder');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/people/sort-order', 'SortController@castAndCrewSortOrder');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/cta/sort-order', 'SortController@ctaSortOrder');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/featured/sort-order', 'SortController@featuredSortOrder');
	Route::post('admin/movies/{movie_id}/edit/sections/{section_id}/gallery/sort-order', 'SortController@gallerySortOrder');
	
	
});

