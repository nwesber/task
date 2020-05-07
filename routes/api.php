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

Route::group([ 'prefix' => 'task',  'middleware' => [ 'auth:api' ], 'as' => 'api.tasks.',], 
	function () {
        Route::get('/', 'TaskController@index')->name('index');
        Route::post('/', 'TaskController@store')->name('store');
        Route::put('done/{id}', 'TaskController@done')->name('done');
        Route::put('/{id}', 'TaskController@update')->name('update');
        Route::delete('/{id}', 'TaskController@destroy')->name('destroy');
	}
);