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

Route::get('/', 'Admin\AnimeController@front');

//誰でも見られる部分
Route::group(['prefix' => 'admin'], function() {
    Route::get('anime','Admin\AnimeController@index');
    Route::post('anime/comment','Admin\CommentsController@store');  
    Route::get('anime/comment','Admin\AnimeController@show');
});

//ログイン画面にリダイレクト処理するリンク
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('anime/create', 'Admin\AnimeController@add');
    Route::post('anime/create', 'Admin\AnimeController@create');
    Route::get('anime/edit','Admin\AnimeController@edit');
    Route::post('anime/edit', 'Admin\AnimeController@update');
    Route::get('anime/delete', 'Admin\AnimeController@delete');
    Route::get('anime/mypost', 'Admin\UsersController@mypost');
    Route::get('anime/message/{partner}', 'Admin\MessagesController@index');
    Route::post('anime/message/send', 'Admin\MessagesController@store');
    Route::get('anime/index', 'Admin\MessagesController@indexMem');//chat一覧画面のルート
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//LINEログインルート
Route::get('/login/{provider}', 'Auth\SocialAccountController@redirectToProvider');
Route::get('/login/{provider}/callback', 'Auth\SocialAccountController@handleProviderCallback');
