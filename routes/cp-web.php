<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('anime/create', 'Admin\AnimeController@add');
    Route::post('anime/create', 'Admin\AnimeController@create');
    Route::get('anime/test','Admin\AnimeController@test');
    Route::get('anime','Admin\AnimeController@index');
    Route::get('anime/comment','Admin\AnimeController@show');
    Route::post('anime','Admin\CommentsController@store');
    Route::get('anime/edit','Admin\AnimeController@edit');
    Route::post('anime/edit', 'Admin\AnimeController@update');
    Route::get('anime/delete', 'Admin\AnimeController@delete');
    Route::resource('anime/comment', 'AnimeController@show', ['only' => ['create', 'store', 'show']]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
