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

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home/create', 'HomeController@create')->name('home.create');
Route::get('/home/delete/{id}', 'HomeController@delete')->name('home.delete');

Route::get('/notifications', 'NotificationController@index')->name('notifications');