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

Route::middleware('auth')
->group(function() {

    Route::get('/chat/create', [
        'uses' => 'ChatController@create',
        'as' => 'chat.create'
    ]);

    Route::post('/chat', [
        'uses' => 'ChatController@store',
        'as' => 'chat.store'
    ]);

    Route::get('/chat', [
        'uses' => 'ChatController@index',
        'as' => 'chat.index'
    ]);

    Route::post('/save-chat', [
        'uses' => 'ChatController@saveToSession',
        'as' => 'chat.save-to-session'
    ]);

    Route::post('/clear-chat', [
        'uses' => 'ChatController@deleteSession',
        'as' => 'chat.delete-session'
    ]);

});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
