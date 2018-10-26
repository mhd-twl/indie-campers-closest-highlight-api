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



Route::get('/show_highlights/{point1}/{point2}',
			'RoutePathController@show_highlights')->name('show_highlights');

Route::get('/shortest_route/{point1}/{lat1}/{long2}',
			'RoutePathController@shortest_route')->name('shortest_route');
 

