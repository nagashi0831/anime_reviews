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

//誰でも見られる部分
Route::group(['prefix' => 'admin'], function() {
    Route::get('anime','Admin\AnimeController@index');
    Route::post('anime','Admin\CommentsController@store');
    Route::get('anime/comment','Admin\AnimeController@show');
    Route::resource('anime/comment', 'AnimeController@show', ['only' => ['create', 'store', 'show']]);
});

//ログイン画面にリダイレクト処理するリンク
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('anime/create', 'Admin\AnimeController@add');
    Route::post('anime/create', 'Admin\AnimeController@create');
    Route::get('anime/edit','Admin\AnimeController@edit');
    Route::post('anime/edit', 'Admin\AnimeController@update');
    Route::get('anime/delete', 'Admin\AnimeController@delete');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
