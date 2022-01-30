<?php

use Illuminate\Support\Facades\Route;

Route::post('/users', 'UsersController@store');
Route::post('/import', 'UsersController@import');

Route::middleware('manual.auth')->group(function () {
	Route::patch('/users', 'UsersController@update');
	Route::delete('/users', 'UsersController@delete');

	Route::post('/relation', 'RelationsController@store');
	Route::delete('/relation', 'RelationsController@delete');
	Route::get('/relation/list/{depth}', 'RelationsController@index');
	Route::get('/relation/random', 'RelationsController@random');
});